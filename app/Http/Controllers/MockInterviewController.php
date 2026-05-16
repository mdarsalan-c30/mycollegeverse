<?php
namespace App\Http\Controllers;

use App\Models\InterviewSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class MockInterviewController extends Controller
{
    private $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private $groqAudioUrl = 'https://api.groq.com/openai/v1/audio/transcriptions';
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
        if (!$request->hasFile('audio')) {
            return response()->json(['status' => 'error', 'message' => 'No audio file provided']);
        }

        // Using Groq Whisper for longer duration support (up to 25MB)
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . trim(config('services.groq.key')),
        ])->attach(
            'file', file_get_contents($request->file('audio')), 'audio.wav'
        )->post($this->groqAudioUrl, [
            'model' => 'whisper-large-v3',
        ]);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Groq STT Error: ' . $response->body()]);
        }

        $data = $response->json();
        return response()->json([
            'status' => 'success',
            'transcript' => $data['text'] ?? ''
        ]);
    }

    public function think(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:interview_sessions,id',
            'message' => 'required|string',
            'wrap_up' => 'boolean'
        ]);

        $session = InterviewSession::findOrFail($request->session_id);
        $history = $session->transcript ?? [];

        // Build prompt
        $wrapUpInst = $request->wrap_up ? " IMPORTANT: The candidate wants to wrap up. Ask one final concluding question (e.g., 'Do you have any questions for us?' or 'Why should we hire you?') and then prepare to end the conversation." : "";

        $systemPrompt = "You are an expert technical interviewer for a " . $session->role . " role. 
        Your goal is to conduct a high-fidelity, professional interview. 
        Ask one question at a time. Keep responses concise and natural. 
        Wait for user input before proceeding. 
        If they answer well, acknowledge it briefly and move to a slightly harder question.
        If they answer poorly, guide them or ask a clarifying question." . $wrapUpInst;

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

        // Split text into sentences or chunks < 500 chars to avoid Sarvam limit
        $text = $request->text;
        $chunks = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Ensure no single chunk is > 500 chars (defensive)
        $finalChunks = [];
        foreach($chunks as $chunk) {
            if (strlen($chunk) > 450) {
                $subChunks = str_split($chunk, 450);
                foreach($subChunks as $sc) $finalChunks[] = $sc;
            } else {
                $finalChunks[] = $chunk;
            }
        }

        try {
            $response = Http::withHeaders([
                'api-subscription-key' => trim(config('services.sarvam.key')),
                'Content-Type' => 'application/json'
            ])->post($this->sarvamTtsUrl, [
                'inputs' => $finalChunks,
                'target_language_code' => 'en-IN',
                'speaker' => 'ritu',
                'model' => 'bulbul:v3'
            ]);

            if ($response->failed()) {
                return response()->json(['status' => 'error', 'message' => 'Sarvam TTS Error: ' . $response->body()]);
            }

            $data = $response->json();
            
            return response()->json([
                'status' => 'success',
                'audio_base64' => $data['audio_base64'] ?? null,
                'audios' => $data['audios'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Voice Engine Exception: ' . $e->getMessage()]);
        }
    }

    public function generateReport(Request $request)
    {
        try {
            $request->validate(['session_id' => 'required|exists:interview_sessions,id']);
            $session = InterviewSession::where('id', $request->session_id)
                                        ->where('user_id', Auth::id())
                                        ->firstOrFail();

            if (!$session->transcript || count($session->transcript) < 2) {
                return response()->json(['status' => 'error', 'message' => 'Interview too short for meaningful analysis.']);
            }

            // Truncate transcript if too long (keep last 15 messages)
            $transcript = array_slice($session->transcript, -15);
            $transcriptText = "";
            foreach($transcript as $entry) {
                $role = isset($entry['user']) ? 'Candidate' : 'Interviewer';
                $text = $entry['user'] ?? ($entry['ai'] ?? ($entry['text'] ?? ''));
                $transcriptText .= "$role: $text\n";
            }

            $analysisPrompt = "Analyze this mock interview transcript for a " . $session->role . " role. 
            Provide a JSON response with exactly two keys:
            1. 'score': A number between 0 and 100 based on the quality of the candidate's answers.
            2. 'feedback': A detailed professional summary including Strengths, Weaknesses, and Advice for improvement. 
            Keep the feedback in plain text or simple markdown.
            
            Transcript:
            " . $transcriptText;

            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . trim(config('services.groq.key')),
                'Content-Type' => 'application/json'
            ])->post($this->groqUrl, [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a senior career coach and technical recruiter. Output only valid JSON.'],
                    ['role' => 'user', 'content' => $analysisPrompt]
                ],
                'response_format' => ['type' => 'json_object'],
                'max_tokens' => 1024
            ]);

            if ($response->failed()) {
                throw new \Exception("Brain Module Timed Out or Errored: " . $response->body());
            }

            $data = $response->json();
            $analysis = json_decode($data['choices'][0]['message']['content'], true);

            $session->update([
                'score' => $analysis['score'] ?? 0,
                'feedback' => $analysis['feedback'] ?? 'No feedback generated.',
                'status' => 'completed'
            ]);

            return response()->json([
                'status' => 'success',
                'score' => $session->score,
                'feedback' => $session->feedback
            ]);

        } catch (\Exception $e) {
            \Log::error("Interview Report Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Intelligence Analysis Failed: ' . $e->getMessage()]);
        }
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
                    'speaker' => 'ritu',
                    'model' => 'bulbul:v3'
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
