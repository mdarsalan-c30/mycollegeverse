<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    /**
     * Manifest the latest changes from the multiverse 🛰️
     */
    public function deploy($token)
    {
        // Security Gate
        if ($token !== env('DEPLOY_TOKEN')) {
            Log::warning('Unauthorized Deployment Attempt Blocked! 🛑');
            abort(403, 'Unauthorized Access to Multiverse Core.');
        }

        $output = [];
        $output[] = "--- Multiverse Sync Sequence Started ---";

        try {
            // 1. Pull Latest Code from GitHub (If shell_exec is allowed)
            if (function_exists('shell_exec')) {
                $gitOutput = shell_exec('git pull origin master 2>&1');
                $output[] = "Git Pull: " . ($gitOutput ?: "No output or failed.");
            } else {
                $output[] = "Git Pull: Skipped (shell_exec disabled on server).";
            }

            // 2. Run Migrations
            Artisan::call('migrate', ['--force' => true]);
            $output[] = "Migrations: Success.";

            // 3. Clear All Caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $output[] = "Cache Remediation: Success.";

            $output[] = "--- Multiverse Sync Complete! 🚀 ---";

            return response()->json([
                'status' => 'success',
                'message' => 'Multiverse Manifested Successfully!',
                'log' => $output
            ]);

        } catch (\Throwable $e) {
            Log::error('Deployment Failure: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'log' => $output
            ], 500);
        }
    }
}
