<?php
// Imagick & GD Check Script
header('Content-Type: application/json');

$results = [
    'imagick' => extension_loaded('imagick'),
    'gd' => extension_loaded('gd'),
    'php_version' => PHP_VERSION,
];

echo json_encode($results);
