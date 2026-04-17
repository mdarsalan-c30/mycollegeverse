<?php
// TEMPORARY DEBUG FILE - DELETE AFTER USE
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>🔍 MyCollegeVerse - Production Fix v3</h2><hr>";

// Properly parse .env (handles = in values like APP_KEY)
$envPath = dirname(__DIR__) . '/.env';
$env = [];
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        $pos = strpos($line, '=');
        if ($pos === false) continue;
        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos + 1));
        $val = trim($val, '"\'');
        $env[$key] = $val;
    }
}

$host = $env['DB_HOST'] ?? 'NOT SET';
$db   = $env['DB_DATABASE'] ?? 'NOT SET';
$user = $env['DB_USERNAME'] ?? 'NOT SET';
$pass = $env['DB_PASSWORD'] ?? '';

echo "<b>DB Host:</b> $host<br>";
echo "<b>DB Name:</b> $db<br>";
echo "<b>DB User:</b> $user<br>";
echo "<b>DB Pass:</b> " . (empty($pass) ? "❌ EMPTY" : "✅ SET (" . strlen($pass) . " chars)") . "<br><br>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<b style='color:green'>✅ DB Connected!</b><br><br>";

    // Check and fix AI columns
    $stmt = $pdo->query("SHOW COLUMNS FROM notes");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<b>notes columns:</b> " . implode(', ', $cols) . "<br><br>";

    $hasAiContent = in_array('ai_content', $cols);
    $hasNoteType  = in_array('note_type', $cols);

    if ($hasAiContent || $hasNoteType) {
        echo "<b style='color:red'>❌ AI columns found! Dropping now...</b><br>";
        if ($hasAiContent) { $pdo->exec("ALTER TABLE notes DROP COLUMN ai_content"); echo "✅ Dropped ai_content<br>"; }
        if ($hasNoteType)  { $pdo->exec("ALTER TABLE notes DROP COLUMN note_type"); echo "✅ Dropped note_type<br>"; }
        echo "<b style='color:green'>✅ Done!</b><br>";
    } else {
        echo "<b style='color:green'>✅ No AI columns — DB clean!</b><br>";
    }

    // Check migrations
    $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 10");
    $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<br><b>Last 10 migrations:</b><ul>";
    foreach ($rows as $r) echo "<li>$r</li>";
    echo "</ul>";

} catch (Exception $e) {
    echo "<b style='color:red'>❌ DB Error: " . $e->getMessage() . "</b><br>";
}

// Show ACTUAL error message from log (not just stack trace)
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    // Find all ERROR lines with message
    preg_match_all('/\] production\.ERROR: (.+?)(\{|$)/m', $content, $matches);
    if (!empty($matches[1])) {
        $errors = array_unique(array_slice($matches[1], -10)); // Last 10 unique errors
        echo "<hr><b>Last unique errors:</b><ul>";
        foreach ($errors as $err) {
            echo "<li style='color:red;font-size:13px'>" . htmlspecialchars(trim($err)) . "</li>";
        }
        echo "</ul>";
    }
}

echo "<hr><b style='color:red'>⚠️ DELETE THIS FILE AFTER DEBUGGING!</b>";
