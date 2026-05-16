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
            'api-subscription-key' => trim(config('services.sarvam.key'))
        ])->attach(
            'file', file_get_contents($request->file('audio')), 'audio.wav'
        )->post($this->sarvamSttUrl, [
            'model' => $request->model ?? 'saaras_v3',
        ]);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Sarvam STT Error: ' . $response->body()]);
        }

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

        $messages = [['role' => 'system', 'content' => $systemPrompt]];

        foreach($history as $h) {
            if (isset($h['user']) && isset($h['ai'])) {
                $messages[] = ['role' => 'user', 'content' => $h['user']];
                $messages[] = ['role' => 'assistant', 'content' => $h['ai']];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $request->message];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . trim(config('services.groq.key')),
            'Content-Type' => 'application/json'
        ])->post($this->groqUrl, [
            'model' => 'llama-3.1-8b-instant',
            'messages' => $messages,
            'temperature' => 0.7
        ]);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Groq API Error: ' . $response->body()]);
        }

        $aiMessage = $response->json()['choices'][0]['message']['content'] ?? "Error in brain module logic.";

        // Update history
        $history[] = ['user' => $request->message, 'ai' => $aiMessage, 'timestamp' => now()];
        $session->update(['transcript' => $history]);

        return response()->json(['status' => 'success', 'message' => $aiMessage]);
    }

    public function speak(Request $request)
    {
        $request->validate(['text' => 'required|string']);

        $response = Http::withHeaders([
            'api-subscription-key' => trim(config('services.sarvam.key')),
            'Content-Type' => 'application/json'
        ])->post($this->sarvamTtsUrl, [
            'inputs' => [$request->text],
            'target_language_code' => 'hi-IN',
            'speaker' => 'shreya',
            'pitch' => 0,
            'pace' => 1.0,
            'loudness' => 1.5,
            'speech_sample_rate' => 22050,
            'enable_preprocessing' => true,
            'model' => 'bulbul_v3'
        ]);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Sarvam TTS Error: ' . $response->body()]);
        }

        return $response->json();
    }

    public function checkStatus()
    {
        $groqKey = config('services.groq.key');
        $sarvamKey = config('services.sarvam.key');

        $results = [
            'groq' => [
                'configured' => !empty($groqKey),
                'status' => 'Testing...',
                'error' => null
            ],
            'sarvam' => [
                'configured' => !empty($sarvamKey),
                'status' => 'Testing...',
                'error' => null
            ]
        ];

        // Test Groq
        if ($results['groq']['configured']) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . trim($groqKey),
                ])->post($this->groqUrl, [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [['role' => 'user', 'content' => 'hi']],
                    'max_tokens' => 5
                ]);

                if ($response->successful()) {
                    $results['groq']['status'] = 'Active (Connected)';
                } else {
                    $results['groq']['status'] = 'Failed (Service Error)';
                    $results['groq']['error'] = $response->body();
                }
            } catch (\Exception $e) {
                $results['groq']['status'] = 'Connection Exception';
                $results['groq']['error'] = $e->getMessage();
            }
        } else {
            $results['groq']['status'] = 'Not Configured (Missing Key)';
        }

        // Test Sarvam
        if ($results['sarvam']['configured']) {
            try {
                // We'll test TTS with a tiny string
                $response = Http::withHeaders([
                    'api-subscription-key' => trim($sarvamKey),
                ])->post($this->sarvamTtsUrl, [
                    'inputs' => ['hi'],
                    'target_language_code' => 'hi-IN',
                    'speaker' => 'shreya',
                    'model' => 'bulbul_v3'
                ]);

                if ($response->successful()) {
                    $results['sarvam']['status'] = 'Active (Connected)';
                } else {
                    $results['sarvam']['status'] = 'Failed (Service Error)';
                    $results['sarvam']['error'] = $response->body();
                }
            } catch (\Exception $e) {
                $results['sarvam']['status'] = 'Connection Exception';
                $results['sarvam']['error'] = $e->getMessage();
            }
        } else {
            $results['sarvam']['status'] = 'Not Configured (Missing Key)';
        }

        return response()->json([
            'status' => 'Intel Diagnostics Complete',
            'timestamp' => now()->toDateTimeString(),
            'diagnostics' => $results
        ]);
    }
}
