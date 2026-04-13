<?php

/**
 * Multiverse Deployment Dashboard 🛰️
 * Use this script to verify if your latest code is actually live on Hostinger.
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

echo "<!DOCTYPE html><html><head><title>MCV Deployment Audit</title><style>body{font-family:sans-serif;background:#0f172a;color:#f1f5f9;padding:50px; line-height:1.6;} .card{background:#1e293b; padding:30px; border-radius:30px; border:1px solid #334155; box-shadow:0 20px 50px rgba(0,0,0,0.5);} h1{color:#3b82f6;} .status{font-weight:bold; color:#10b981;} code{background:#000; padding:15px; display:block; border-radius:15px; margin:20px 0; color:#8de;} .btn{display:inline-block; padding:15px 30px; background:#3b82f6; color:#fff; text-decoration:none; border-radius:15px; font-weight:bold; margin-top:20px;}</style></head><body>";

echo "<div class='card'>";
echo "<h1>🛰️ Deployment Audit Node</h1>";
echo "<p>Checking synchronization status for <strong>MyCollegeVerse</strong> Master Registry...</p>";

// Target Commit Message we are looking for:
$targetMessage = "Multiverse Identity Isolation: Deployed context-aware auth steering and high-fidelity role isolation.";

echo "<ul>";
echo "<li><strong>Environment:</strong> " . (php_sapi_name() === 'fpm-fcgi' ? 'Production (Hostinger)' : 'Local/Unknown') . "</li>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>Laravel Version:</strong> " . $app->version() . "</li>";
echo "<li><strong>App Key Status:</strong> " . (env('APP_KEY') ? "<span class='status'>✅ Generated</span>" : "<span style='color:#ef4444;'>❌ MISSING (Run php artisan key:generate)</span>") . "</li>";
echo "</ul>";

echo "<h3>Check Instructions:</h3>";
echo "<p>If the code below does NOT show 'Control Center' or 'mcv-admin' mentions, then Hostinger is still running an OLD version of your code.</p>";

echo "<h3>System Commands:</h3>";
echo "<p>Try visiting these nodes to force a reset:</p>";
echo "<a href='/multiverse-sync' class='btn'>Force Multiverse Sync</a> ";
echo "<a href='/reboot.php' class='btn'>Emergency Reboot</a>";

echo "<h3>Route Test:</h3>";
echo "<p>The new Admin Terminal is now at: <a href='/mcv-admin/login' style='color:#3b82f6;'>/mcv-admin/login</a></p>";

echo "<hr style='border: 0; border-top: 1px solid #334155; margin: 40px 0;'>";

echo "<h3>Authority Diagnosis Node 🔎</h3>";
echo "<p>Check the role of any email in the system:</p>";
echo "<form method='GET' style='display:flex; gap:10px;'>
        <input type='email' name='check_email' placeholder='shami@gmail.com' required style='padding:10px; border-radius:10px; border:1px solid #334155; background:#0f172a; color:#fff; flex:1;'>
        <button type='submit' style='padding:10px 20px; background:#10b981; color:#fff; border:none; border-radius:10px; cursor:pointer;'>Check Role</button>
      </form>";

if (isset($_GET['check_email'])) {
    $email = $_GET['check_email'];
    $user = \App\Models\User::where('email', $email)->first();
    
    echo "<div style='margin-top:20px; padding:20px; background:#000; border-radius:15px; border:1px solid #10b981;'>";
    if ($user) {
        echo "<p><strong>Email:</strong> " . e($user->email) . "</p>";
        echo "<p><strong>Current Role (Raw):</strong> <span style='color:#10b981;'>'" . e($user->role) . "'</span></p>";
        echo "<p><strong>Result:</strong> " . (strtolower(trim($user->role)) === 'admin' ? "✅ AUTHORIZED FOR ADMIN ACCESS" : "❌ DENIED (Not an admin)") . "</p>";
    } else {
        echo "<p style='color:#ef4444;'>❌ User not found in database.</p>";
    }
    echo "</div>";
}

echo "</div></body></html>";
