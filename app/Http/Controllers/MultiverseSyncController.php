<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class MultiverseSyncController extends Controller
{
    /**
     * Establish the Verse Nexus and synchronize the database schema.
     * This bypasses terminal restrictions on Hostinger.
     */
    public function sync()
    {
        try {
            // 1. Establish Nexus Points (Clear Caches)
            Artisan::call('optimize:clear');
            
            // 2. Run Migrations
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();
            
            Log::info("Migration Output: " . $output);

            return response()->json([
                'status' => 'success',
                'message' => 'Nexus Link Established. Database Synchronized.',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            Log::error("Nexus Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Nexus Synchronization Failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
