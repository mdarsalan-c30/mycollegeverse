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
        $session = InterviewSession::create([
            'user_id' => Auth::id(),
            'role' => $request->role,
            'transcript' => [],
            'total_questions' => 20, // Long session, user will wrap up
            'status' => 'active'
        ]);

        return response()->json(['status' => 'success', 'session_id' => $session->id]);
    }

    public function transcribe(Request $request)
    {
        if (!$request->hasFile('audio')) {
            return response()->json(['status' => 'error', 'message' => 'No audio file provided']);
        }

        // Switching back to Sarvam STT for better accuracy as requested
        $response = Http::withHeaders([
            'api-subscription-key' => trim(config('services.sarvam.key')),
        ])->attach(
            'file', file_get_contents($request->file('audio')), 'audio.wav'
        )->post($this->sarvamSttUrl, [
            'model' => 'saarika:v2',
            'language_code' => 'hi-IN'
        ]);

        if ($response->failed()) {
            return response()->json(['status' => 'error', 'message' => 'Transcription Error: ' . $response->body()]);
        }

        $data = $response->json();
        return response()->json([
            'status' => 'success',
            'transcript' => $data['transcript'] ?? ''
        ]);
    }

    public function think(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:interview_sessions,id',
            'message' => 'required|string'
        ]);

        $session = InterviewSession::findOrFail($request->session_id);
        $history = $session->transcript ?? [];

        $total = $session->total_questions ?? 5;
        $current = ($session->current_question_count ?? 0) + 1;

        // Build prompt with awareness of progress
        $systemPrompt = "You are an expert technical interviewer for a " . $session->role . " role. 
        PROGRESS: This is question " . $current . " out of " . $total . ".
        
        Guidelines:
        1. Ask one question at a time. Keep responses concise.
        2. If this is the final question ( " . $current . " == " . $total . " ), acknowledge the answer and formally conclude the interview.
        3. If the user indicates they are in a hurry (wrap up), conclude the interview gracefully in this turn.
        
        Wait for user input before proceeding. If they answer well, acknowledge briefly and move to a harder question.";

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
        
        $updateData = ['transcript' => $history];
        
        // Defensive progress tracking
        $hasProgressCols = \Schema::hasColumn('interview_sessions', 'current_question_count');
        if ($hasProgressCols) {
            $updateData['current_question_count'] = $session->current_question_count + 1;
        }
        
        $session->update($updateData);

        $isFinal = false;
        // Check if limit reached OR if wrap up signal was sent in this turn
        if ($hasProgressCols && \Schema::hasColumn('interview_sessions', 'total_questions')) {
            $isFinal = ($session->current_question_count >= $session->total_questions) || (strpos($request->message, '[SYSTEM: User is in a hurry]') !== false);
        }

        return response()->json([
            'status' => 'success', 
            'message' => $aiMessage,
            'is_final' => $isFinal,
            'current_q' => $session->current_question_count ?? 0,
            'total_q' => $session->total_questions ?? 0
        ]);
    }

    public function speak(Request $request)
    {
        $request->validate(['text' => 'required|string']);
        $text = $request->text;

        // Sarvam Bulbul:v3 has a 500 char limit. We must split by sentence.
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $chunks = [];
        $currentChunk = "";

        foreach ($sentences as $sentence) {
            if (strlen($currentChunk . " " . $sentence) < 450) {
                $currentChunk .= ($currentChunk ? " " : "") . $sentence;
            } else {
                if ($currentChunk) $chunks[] = $currentChunk;
                $currentChunk = $sentence;
                
                // If a single sentence is still > 450, force split it
                while (strlen($currentChunk) > 450) {
                    $chunks[] = substr($currentChunk, 0, 450);
                    $currentChunk = substr($currentChunk, 450);
                }
            }
        }
        if ($currentChunk) $chunks[] = $currentChunk;

        $audios = [];

        foreach ($chunks as $chunk) {
            $response = Http::withHeaders([
                'api-subscription-key' => trim(config('services.sarvam.key')),
                'Content-Type' => 'application/json'
            ])->post($this->sarvamTtsUrl, [
                'inputs' => [$chunk],
                'target_language_code' => 'hi-IN',
                'speaker' => 'aditya', // Corrected name based on error message
                'model' => 'bulbul:v3',
                'speech_sample_rate' => 16000,
                'enable_preprocessing' => true
            ]);

            if ($response->successful()) {
                $audios[] = $response->json()['audios'][0] ?? null;
            } else {
                \Log::error("Sarvam TTS Chunk Error: " . $response->body());
            }
        }

        if (empty($audios)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate any audio chunks.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'audios' => array_filter($audios)
        ]);
    }

    public function generateReport(Request $request)
    {
        $request->validate(['session_id' => 'required|exists:interview_sessions,id']);
        $session = InterviewSession::where('id', $request->session_id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();

        if (!$session->transcript || count($session->transcript) < 2) {
            return response()->json(['status' => 'error', 'message' => 'Interview too short for meaningful analysis.']);
        }

        $transcriptText = "";
        foreach($session->transcript as $entry) {
            $transcriptText .= $entry['role'] . ": " . $entry['text'] . "\n";
        }

        $analysisPrompt = "Analyze this mock interview transcript for a " . $session->role . " role. 
        Provide a JSON response with exactly two keys:
        1. 'score': A number between 0 and 100 based on the quality of the candidate's answers.
        2. 'feedback': A detailed professional summary including Strengths, Weaknesses, and Advice for improvement. 
        Keep the feedback in plain text or simple markdown.
        
        Transcript:
        " . $transcriptText;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . trim(config('services.groq.key')),
                'Content-Type' => 'application/json'
            ])->post($this->groqUrl, [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a senior career coach. Analyze the interview transcript. Output valid JSON with keys "score" (0-100) and "feedback" (detailed string).'],
                    ['role' => 'user', 'content' => $analysisPrompt]
                ],
                'response_format' => ['type' => 'json_object']
            ]);

            if ($response->failed()) {
                throw new \Exception("Brain analysis failed: " . $response->body());
            }

            $rawContent = $response->json()['choices'][0]['message']['content'];
            $analysis = json_decode($rawContent, true);

            if (!$analysis || !isset($analysis['score'])) {
                // Fallback parsing if JSON is slightly malformed
                preg_match('/"score":\s*(\d+)/', $rawContent, $scoreMatch);
                preg_match('/"feedback":\s*"(.*)"/s', $rawContent, $feedbackMatch);
                
                $analysis = [
                    'score' => $scoreMatch[1] ?? 70,
                    'feedback' => $feedbackMatch[1] ?? "Interview completed successfully. Good effort!"
                ];
            }

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
