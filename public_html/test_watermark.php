<?php
// PDF Watermark Test Script using Imagick
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (!extension_loaded('imagick')) {
        die("Imagick not loaded.");
    }

    $siteText = "MYCOLLEGEVERSE.IN";
    $authorText = "Verified Author: Arsalan";

    // 1. Create a blank "mock" PDF page using Imagick
    $canvas = new Imagick();
    $canvas->newImage(595, 842, new ImagickPixel('white')); // A4 size
    $canvas->setImageFormat('pdf');

    // 2. Add Watermark Layer
    $draw = new ImagickDraw();
    $draw->setFillColor('#94a3b8');
    $draw->setFontSize(24);
    $draw->setFontWeight(700);
    $draw->setFillAlpha(0.2); // Semi-transparent
    
    // Center Logo Text
    $canvas->annotateImage($draw, 150, 421, 45, $siteText);

    // Footer Text
    $draw->setFillAlpha(0.8);
    $draw->setFontSize(14);
    $canvas->annotateImage($draw, 30, 810, 0, "Downloaded from mycollegeverse.in");
    $canvas->annotateImage($draw, 350, 810, 0, $authorText);

    // 3. Output as a test file
    $testPath = public_path('test_branded.pdf');
    $canvas->writeImage($testPath);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Branded PDF created!',
        'url' => url('test_branded.pdf')
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

function public_path($path = '') {
    return __DIR__ . ($path ? DIRECTORY_SEPARATOR . $path : $path);
}

function url($path = '') {
    return 'https://' . $_SERVER['HTTP_HOST'] . '/' . $path;
}
