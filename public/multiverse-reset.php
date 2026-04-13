<?php

/**
 * Multiverse Identity Reset Node 🛰️⚔️
 * WARNING: This script will delete ALL users and seed fresh test accounts.
 */

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "<!DOCTYPE html><html><head><title>Identity Reset Terminal</title><style>body{font-family:sans-serif;background:#0f172a;color:#f1f5f9;padding:50px; line-height:1.6;} .card{background:#1e293b; padding:30px; border-radius:30px; border:1px solid #334155; box-shadow:0 20px 50px rgba(0,0,0,0.5);} h1{color:#ef4444;} .success{color:#10b981; font-weight:bold;} code{background:#000; padding:15px; display:block; border-radius:15px; margin:20px 0; color:#8de;}</style></head><body>";

echo "<div class='card'>";
echo "<h1>🛰️ Master Identity Reset Terminal</h1>";

try {
    // 1. Wipe everything
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    User::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    $password = Hash::make('123@Qwerty');

    // 2. Seed Admin
    User::create([
        'name' => 'Master Admin',
        'email' => 'admin@gmail.com',
        'username' => 'super-admin',
        'password' => $password,
        'role' => 'admin',
        'status' => 'active'
    ]);

    // 3. Seed Student
    User::create([
        'name' => 'Student User',
        'email' => 'student@gmail.com',
        'username' => 'student-user',
        'password' => $password,
        'role' => 'student',
        'status' => 'active'
    ]);

    // 4. Seed Recruiter
    User::create([
        'name' => 'Recruitment Partner',
        'email' => 'study@gmail.com',
        'username' => 'recruiter-partner',
        'password' => $password,
        'role' => 'recruiter',
        'company_name' => 'MyCollegeVerse Corp',
        'company_website' => 'https://mycollegeverse.in',
        'status' => 'active'
    ]);

    echo "<p class='success'>✅ DATABASE IDENTITY RESET SUCCESSFUL!</p>";
    echo "<h3>New Credentials (Password: 123@Qwerty):</h3>";
    echo "<ul>
            <li><strong>Admin:</strong> admin@gmail.com (at /mcv-admin/login)</li>
            <li><strong>Student:</strong> student@gmail.com (at /login)</li>
            <li><strong>Recruiter:</strong> study@gmail.com (at /recruiter/login)</li>
          </ul>";
    
    echo "<p>Visit the new Admin Terminal: <a href='/mcv-admin/login' style='color:#3b82f6;'>/mcv-admin/login</a></p>";

} catch (\Exception $e) {
    echo "<p style='color:#ef4444;'>❌ RESET ERROR: " . $e->getMessage() . "</p>";
}

echo "</div></body></html>";
