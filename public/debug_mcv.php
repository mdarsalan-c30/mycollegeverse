<?php
// MyCollegeVerse Diagnostic Script 🛰️

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🛰️ MCV Diagnostic Node Active</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";

$paths = [
    'Vendor' => __DIR__.'/../vendor/autoload.php',
    'Bootstrap' => __DIR__.'/../bootstrap/app.php',
    'Env' => __DIR__.'/../.env'
];

foreach ($paths as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name found at: $path<br>";
    } else {
        echo "❌ $name MISSING at: $path<br>";
    }
}

echo "<h2>Checking Database Connection...</h2>";
try {
    $env = file_get_contents(__DIR__.'/../.env');
    preg_match('/DB_HOST=(.*)/', $env, $host);
    preg_match('/DB_DATABASE=(.*)/', $env, $db);
    preg_match('/DB_USERNAME=(.*)/', $env, $user);
    preg_match('/DB_PASSWORD=(.*)/', $env, $pass);
    
    $dsn = "mysql:host=".trim($host[1]).";dbname=".trim($db[1]).";charset=utf8mb4";
    $pdo = new PDO($dsn, trim($user[1]), trim($pass[1]));
    echo "✅ Database Connection Successful!<br>";
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}
?>
