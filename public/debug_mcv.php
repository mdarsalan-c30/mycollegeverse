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
    // High-Precision Credential Parsing 🛰️
    function getEnvValue($key, $content) {
        if (preg_match('/^' . $key . '=(.*)$/m', $content, $matches)) {
            $value = trim($matches[1]);
            return trim($value, '"\' ');
        }
        return null;
    }

    $host = getEnvValue('DB_HOST', $env);
    $dbName = getEnvValue('DB_DATABASE', $env);
    $dbUser = getEnvValue('DB_USERNAME', $env);
    $dbPass = getEnvValue('DB_PASSWORD', $env);
    
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    echo "✅ Database Connection Successful!<br>";
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}
?>
