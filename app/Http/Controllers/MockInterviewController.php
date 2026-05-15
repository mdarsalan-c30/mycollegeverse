<?php
namespace App\Http\Controllers;

use App\Models\InterviewSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class MockInterviewController extends Controller
{
    private $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private $sarvamSttUrl = 'https://api.sarvam.ai/speech-to-text';
    private $sarvamTtsUrl = 'https://api.sarvam.ai/text-to-speech';

    public function index()
    {
        $sessions = InterviewSession::where('user_id', Auth::id())->latest()->get();
        return view('assess.mock-interview', compact('sessions'));
    }

    public function start(Request $request)
    {
        $request->validate(['role' => 'required|string']);

        $session = InterviewSession::create([
            'user_id' => Auth::id(),
            'role' => $request->role,
            'transcript' => [],
            'status' => 'active'
        ]);

        return response()->json(['status' => 'success', 'session_id' => $session->id]);
    }

    public function transcribe(Request $request)
    {
        $request->validate(['audio' => 'required|file', 'model' => 'nullable|string']);

        $response = Http::withHeaders([
            'api-subscription-key' => env('SARVAM_API_KEY')
        ])->attach(
            'file', file_get_contents($request->file('audio')), 'audio.wav'
        )->post($this->sarvamSttUrl, [
            'model' => $request->model ?? 'saaras_v3',
        ]);

        return $response->json();
    }

    public function think(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:interview_sessions,id',
            'message' => 'required|string'
        ]);

        $session = InterviewSession::findOrFail($request->session_id);
        $history = $session->transcript ?? [];

        // Build prompt
        $systemPrompt = "You are an expert technical interviewer for a " . $session->role . " role. 
        Your goal is to conduct a high-fidelity, professional interview. 
        Ask one question at a time. Keep responses concise and natural. 
        Wait for user input before proceeding. 
        If they answer well, acknowledge it briefly and move to a slightly harder question.
        If they answer poorly, guide them or ask a clarifying question.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        foreach($history as $h) {
            $messages[] = ['role' => 'user', 'content' => $h['user']];
            $messages[] = ['role' => 'assistant', 'content' => $h['ai']];
        }

        $messages[] = ['role' => 'user', 'content' => $request->message];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post($this->groqUrl, [
            'model' => 'llama3-70b-8192',
            'messages' => $messages,
            'temperature' => 0.7
        ]);

        $aiMessage = $response->json()['choices'][0]['message']['content'] ?? "Error in brain module.";

        // Update history
        $history[] = ['user' => $request->message, 'ai' => $aiMessage, 'timestamp' => now()];
        $session->update(['transcript' => $history]);

        return response()->json(['status' => 'success', 'message' => $aiMessage]);
    }

    public function speak(Request $request)
    {
        $request->validate(['text' => 'required|string']);

        $response = Http::withHeaders([
            'api-subscription-key' => env('SARVAM_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post($this->sarvamTtsUrl, [
            'inputs' => [$request->text],
            'target_language_code' => 'hi-IN', // Defaulting to high-fidelity Hinglish/Hindi-English
            'speaker' => 'meera', // Professional voice
            'pitch' => 0,
            'pace' => 1.1,
            'loudness' => 1.5,
            'speech_sample_rate' => 22050,
            'enable_preprocessing' => true,
            'model' => 'bulbul_v1'
        ]);

        return $response->json();
    }
}
