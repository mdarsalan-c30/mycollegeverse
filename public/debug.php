<?php
// TEMPORARY DEBUG FILE - DELETE AFTER USE
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>🔍 MyCollegeVerse - Production Diagnostics</h2>";
echo "<hr>";

// 1. PHP Version
echo "<b>PHP Version:</b> " . phpversion() . "<br>";

// 2. Check .env file exists
$envPath = dirname(__DIR__) . '/.env';
echo "<b>.env exists:</b> " . (file_exists($envPath) ? "✅ YES" : "❌ NO - THIS IS THE PROBLEM") . "<br>";

// 3. Check vendor/autoload
$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
echo "<b>vendor/autoload.php exists:</b> " . (file_exists($autoloadPath) ? "✅ YES" : "❌ NO - Run composer install") . "<br>";

// 4. Check storage writeable
$storagePath = dirname(__DIR__) . '/storage';
echo "<b>storage/ writable:</b> " . (is_writable($storagePath) ? "✅ YES" : "❌ NO") . "<br>";

// 5. Check bootstrap/cache writable
$cachePath = dirname(__DIR__) . '/bootstrap/cache';
echo "<b>bootstrap/cache/ writable:</b> " . (is_writable($cachePath) ? "✅ YES" : "❌ NO") . "<br>";

// 6. Try DB connection
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    $host = $env['DB_HOST'] ?? 'NOT SET';
    $db   = $env['DB_DATABASE'] ?? 'NOT SET';
    $user = $env['DB_USERNAME'] ?? 'NOT SET';
    $pass = $env['DB_PASSWORD'] ?? 'NOT SET';

    echo "<hr><b>DB Host:</b> $host<br>";
    echo "<b>DB Name:</b> $db<br>";
    echo "<b>DB User:</b> $user<br>";
    echo "<b>DB Pass:</b> " . (empty($pass) ? "❌ EMPTY" : "✅ SET") . "<br>";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<b>DB Connection:</b> ✅ SUCCESS<br>";

        // Check for pending migrations
        $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 10");
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<hr><b>Last 10 ran migrations:</b><br><ul>";
        foreach ($rows as $r) echo "<li>$r</li>";
        echo "</ul>";

        // Check if AI columns exist
        $stmt2 = $pdo->query("SHOW COLUMNS FROM notes");
        $cols = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        echo "<b>Notes table columns:</b> " . implode(', ', $cols) . "<br>";

    } catch (Exception $e) {
        echo "<b>DB Connection:</b> ❌ FAILED - " . $e->getMessage() . "<br>";
    }
}

// 7. Laravel log last error
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines, -30);
    echo "<hr><b>Last Laravel Log Lines:</b><pre style='background:#111;color:#0f0;padding:10px;font-size:11px'>";
    echo htmlspecialchars(implode('', $last));
    echo "</pre>";
}

echo "<hr><b style='color:red'>⚠️ DELETE THIS FILE AFTER DEBUGGING!</b>";
