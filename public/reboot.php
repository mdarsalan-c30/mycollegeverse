<?php
// Multiverse Emergency Rescue Node 🛰️
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Recalibrating Multiverse Registry...</h1>";
$kernel->call('optimize:clear');
echo "<p>✅ All Caches Terminated Successfully!</p>";
echo "<p>👉 <a href='/admin/login'>Enter Admin Terminal</a></p>";
