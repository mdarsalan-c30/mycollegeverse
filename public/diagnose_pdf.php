<?php
// Imagick & GD Check Script
header('Content-Type: application/json');

$results = [
    'imagick' => extension_loaded('imagick'),
    'gd' => extension_loaded('gd'),
    'php_version' => PHP_VERSION,
    'ghostscript' => shell_exec('gs --version') ? true : false,
];

echo json_encode($results);
