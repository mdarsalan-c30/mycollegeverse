<?php
// TEMPORARY DEBUG FILE - DELETE AFTER USE
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>🔍 Latest Error & Migration Status</h2><hr>";

// DB Connection
$envPath = dirname(__DIR__) . '/.env';
$env = parse_ini_file($envPath);
$host = $env['DB_HOST'] ?? 'NOT SET';
$db   = $env['DB_DATABASE'] ?? 'NOT SET';
$user = $env['DB_USERNAME'] ?? 'NOT SET';
$pass = $env['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<b style='color:green'>✅ DB Connected to: $db on $host</b><br><br>";

    // Show last 15 migrations
    $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY id DESC LIMIT 15");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<b>Last 15 migrations ran:</b><ul>";
    foreach ($rows as $r) echo "<li>Batch {$r['batch']}: {$r['migration']}</li>";
    echo "</ul>";

    // Check if AI columns still exist
    $stmt2 = $pdo->query("SHOW COLUMNS FROM notes");
    $cols = $stmt2->fetchAll(PDO::FETCH_COLUMN);
    echo "<b>notes columns:</b> " . implode(', ', $cols) . "<br><br>";

    $hasAiContent = in_array('ai_content', $cols);
    $hasNoteType  = in_array('note_type', $cols);

    if ($hasAiContent || $hasNoteType) {
        echo "<b style='color:red'>❌ AI columns still exist! Dropping them now...</b><br>";
        if ($hasAiContent) { $pdo->exec("ALTER TABLE notes DROP COLUMN ai_content"); echo "✅ Dropped ai_content<br>"; }
        if ($hasNoteType)  { $pdo->exec("ALTER TABLE notes DROP COLUMN note_type");  echo "✅ Dropped note_type<br>"; }
        // Mark cleanup migration as ran
        $pdo->exec("INSERT IGNORE INTO migrations (migration, batch) VALUES ('2026_04_17_120000_drop_ai_fields_from_notes_table', 999)");
        // Also mark the original AI migration as ran (prevent re-run)
        $pdo->exec("INSERT IGNORE INTO migrations (migration, batch) VALUES ('2026_04_17_070528_add_ai_fields_to_notes_table', 999)");
        echo "<b style='color:green'>✅ AI columns cleaned up! Refresh site now.</b><br>";
    } else {
        echo "<b style='color:green'>✅ No AI columns found — DB looks clean!</b><br>";
    }

} catch (Exception $e) {
    echo "<b style='color:red'>❌ DB Error: " . $e->getMessage() . "</b><br>";
}

// Latest log
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines, -40);
    echo "<hr><b>Last 40 log lines:</b><pre style='background:#111;color:#0f0;padding:10px;font-size:11px;overflow:auto;max-height:400px'>";
    echo htmlspecialchars(implode('', $last));
    echo "</pre>";
}

echo "<hr><b style='color:red'>⚠️ DELETE THIS FILE AFTER DEBUGGING!</b>";
