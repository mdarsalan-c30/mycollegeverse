<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

// Emergency Multiverse Storage Fix 🛰️
Route::get('/multiverse-storage-link', function () {
    try {
        // Remove existing link if it's broken
        if (is_link(public_path('storage'))) {
            unlink(public_path('storage'));
        } elseif (is_dir(public_path('storage'))) {
            // If it's a real directory instead of a link, we might need to move content
            // But usually on Hostinger it's better to just link it
        }

        Artisan::call('storage:link');
        
        // Create the academic-guides directory just in case
        Storage::disk('public')->makeDirectory('academic-guides');
        
        return "🌌 Multiverse Storage Link Manifested Successfully! PDFs should now be visible.";
    } catch (\Exception $e) {
        return "❌ Error Manifesting Link: " . $e->getMessage();
    }
});
