<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    /**
     * Manifest Deployment Hub 🛰️
     * Secret key is required to prevent unauthorized multiverse sync.
     */
    public function sync($secret)
    {
        if ($secret !== 'MCV_SYNC_PROTCOL_77') {
            return response()->json(['error' => 'Unauthorized Access Attempt'], 403);
        }

        try {
            Log::info('Initiating Production Sync...');
            
            // 1. Clear Stale Knowledge Nodes
            Artisan::call('optimize:clear');
            
            // 2. Sync Database Schema
            Artisan::call('migrate', ['--force' => true]);
            
            // 3. Link Storage Node (Shared hosting workaround)
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }

            return "🌌 Multiverse Synchronized! Deployment Successful.";
        } catch (\Exception $e) {
            Log::error('Sync Error: ' . $e->getMessage());
            return "Sync Failed: " . $e->getMessage();
        }
    }

    public function clearCache($secret)
    {
        if ($secret !== 'MCV_SYNC_PROTCOL_77') return abort(403);
        
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        
        return "⚡ Cache Cleared. UI manifest refreshed.";
    }
}
