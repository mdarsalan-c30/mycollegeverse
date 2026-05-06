<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupExpiredSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcv:cleanup-submissions';
    protected $description = 'Deletes expired assignment submissions and their assets to save space node integrity.';

    public function handle()
    {
        $expired = \App\Models\AssignmentSubmission::where('expires_at', '<', now())->get();
        $count = 0;

        foreach ($expired as $submission) {
            // 1. Cloudinary Cleanup (If applicable)
            if ($submission->file_id) {
                try {
                    $this->deleteFromCloudinary($submission->file_id);
                } catch (\Exception $e) {
                    $this->error("Failed to delete Cloudinary asset: " . $e->getMessage());
                }
            }

            // 2. Delete the submission record entirely or just clear sensitive data
            $submission->delete();
            $count++;
        }

        $this->info("Successfully purged $count expired submission nodes from the multiverse.");
    }

    private function deleteFromCloudinary($publicId)
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        $timestamp = time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        
        // Simple signature generation logic
        ksort($params);
        $paramString = "";
        foreach ($params as $key => $value) {
            $paramString .= "$key=$value&";
        }
        $paramString = rtrim($paramString, '&');
        $signature = sha1($paramString . $apiSecret);

        \Illuminate\Support\Facades\Http::post("https://api.cloudinary.com/v1_1/{$cloudName}/image/destroy", [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'api_key' => $apiKey,
            'signature' => $signature,
        ]);
    }
}
