<?php

// Test script to debug WikiMedia API results for MyCollegeVerse
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$queries = ['Hindu College Delhi', 'IIT Delhi', 'St. Stephens College Delhi'];

foreach ($queries as $query) {
    echo "Testing query: $query\n";
    
    // Attempt 1: The current logic (but simplified)
    $response = Http::get('https://commons.wikimedia.org/w/api.php', [
        'action' => 'query',
        'format' => 'json',
        'generator' => 'search',
        'gsrsearch' => $query,
        'gsrnamespace' => 6,
        'gsrlimit' => 5,
        'prop' => 'imageinfo',
        'iiprop' => 'url|mime',
    ]);

    echo "Status: " . $response->status() . "\n";
    if ($response->successful()) {
        $data = $response->json();
        if (isset($data['query']['pages'])) {
            echo "Found " . count($data['query']['pages']) . " results!\n";
            foreach($data['query']['pages'] as $page) {
                echo " - " . ($page['title'] ?? 'No Title') . "\n";
            }
        } else {
            echo "No pages found in response.\n";
            print_r($data);
        }
    } else {
        echo "Request Failed!\n";
    }
    echo "--------------------------\n";
}
