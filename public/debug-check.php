<?php
// Temporary diagnostic script - DELETE after debugging
echo "<h2>Railway Diagnostic Check</h2>";
echo "<pre>";

// 1. PHP Version
echo "PHP Version: " . phpversion() . "\n\n";

// 2. Check extensions
echo "=== PHP Extensions ===\n";
echo "PDO: " . (extension_loaded('pdo') ? 'YES' : 'NO') . "\n";
echo "pdo_mysql: " . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "\n";
echo "mysqli: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "\n";
echo "openssl: " . (extension_loaded('openssl') ? 'YES' : 'NO') . "\n";
echo "mbstring: " . (extension_loaded('mbstring') ? 'YES' : 'NO') . "\n";
echo "tokenizer: " . (extension_loaded('tokenizer') ? 'YES' : 'NO') . "\n";
echo "xml: " . (extension_loaded('xml') ? 'YES' : 'NO') . "\n";
echo "ctype: " . (extension_loaded('ctype') ? 'YES' : 'NO') . "\n";
echo "json: " . (extension_loaded('json') ? 'YES' : 'NO') . "\n";
echo "fileinfo: " . (extension_loaded('fileinfo') ? 'YES' : 'NO') . "\n";
echo "curl: " . (extension_loaded('curl') ? 'YES' : 'NO') . "\n\n";

// 3. Check env vars
echo "=== Environment Variables ===\n";
$vars = ['APP_NAME','APP_ENV','APP_DEBUG','APP_URL','APP_KEY',
         'DB_CONNECTION','DB_HOST','DB_PORT','DB_DATABASE','DB_USERNAME','DB_PASSWORD',
         'SESSION_DRIVER','CACHE_STORE','QUEUE_CONNECTION'];
foreach ($vars as $var) {
    $val = getenv($var);
    if ($var === 'DB_PASSWORD' && $val) {
        echo "$var: " . str_repeat('*', strlen($val)) . "\n";
    } elseif ($var === 'APP_KEY' && $val) {
        echo "$var: " . substr($val, 0, 10) . "...\n";
    } else {
        echo "$var: " . ($val ?: '(NOT SET)') . "\n";
    }
}
echo "\n";

// 4. Check database connection
echo "=== Database Connection Test ===\n";
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: '';
$dbUser = getenv('DB_USERNAME') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: '';

if (extension_loaded('pdo_mysql')) {
    try {
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_TIMEOUT => 5]);
        echo "Connection: SUCCESS\n";
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables found: " . count($tables) . "\n";
        echo "Tables: " . implode(', ', array_slice($tables, 0, 10));
        if (count($tables) > 10) echo "... and " . (count($tables) - 10) . " more";
        echo "\n";
    } catch (PDOException $e) {
        echo "Connection: FAILED\n";
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "pdo_mysql extension NOT loaded - cannot test DB\n";
}
echo "\n";

// 5. Check file permissions
echo "=== File System ===\n";
$dirs = [
    '../storage' => __DIR__ . '/../storage',
    '../storage/logs' => __DIR__ . '/../storage/logs',
    '../storage/framework' => __DIR__ . '/../storage/framework',
    '../storage/framework/sessions' => __DIR__ . '/../storage/framework/sessions',
    '../storage/framework/views' => __DIR__ . '/../storage/framework/views',
    '../storage/framework/cache' => __DIR__ . '/../storage/framework/cache',
    '../bootstrap/cache' => __DIR__ . '/../bootstrap/cache',
];
foreach ($dirs as $name => $path) {
    $exists = is_dir($path) ? 'EXISTS' : 'MISSING';
    $writable = is_writable($path) ? 'writable' : 'NOT writable';
    echo "$name: $exists, $writable\n";
}
echo "\n";

// 6. Check Laravel log
echo "=== Latest Laravel Log ===\n";
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $log = file_get_contents($logFile);
    // Get last 3000 chars
    echo htmlspecialchars(substr($log, -3000));
} else {
    echo "No log file found at: $logFile\n";
}

echo "</pre>";
