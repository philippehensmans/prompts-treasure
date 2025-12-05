<?php
// This script writes diagnostic info to a file instead of displaying it
$output = "";
$output .= "=== DIAGNOSTIC REPORT ===\n";
$output .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";

$output .= "PHP Version: " . phpversion() . "\n";
$output .= "__DIR__: " . __DIR__ . "\n";
$output .= "__FILE__: " . __FILE__ . "\n\n";

$output .= "=== Testing auth.php paths ===\n";
$test_paths = [
    __DIR__ . '/../auth.php',
    __DIR__ . '/../../auth.php',
    '/www/exercices/auth.php',
    '/www/auth.php',
    '/var/www/exercices/auth.php',
    '/home/user/auth.php'
];

foreach ($test_paths as $path) {
    $exists = file_exists($path) ? 'EXISTS ✓' : 'NOT FOUND ✗';
    $output .= "$path => $exists\n";
}

$output .= "\n=== Testing config.php ===\n";
$config_path = __DIR__ . '/config.php';
$output .= "$config_path => " . (file_exists($config_path) ? 'EXISTS ✓' : 'NOT FOUND ✗') . "\n";

$output .= "\n=== PHP Extensions ===\n";
$output .= "PDO: " . (extension_loaded('pdo') ? 'YES ✓' : 'NO ✗') . "\n";
$output .= "SQLite3: " . (extension_loaded('sqlite3') ? 'YES ✓' : 'NO ✗') . "\n";
$output .= "PDO_SQLITE: " . (extension_loaded('pdo_sqlite') ? 'YES ✓' : 'NO ✗') . "\n";

$output .= "\n=== Parent directory files ===\n";
$parent_dir = dirname(__DIR__);
$output .= "Parent directory: $parent_dir\n";
if (is_dir($parent_dir)) {
    $files = scandir($parent_dir);
    $output .= "Files: " . implode(', ', array_filter($files, function($f) { return $f[0] !== '.'; })) . "\n";
}

// Write to file
$log_file = __DIR__ . '/diagnostic-output.txt';
file_put_contents($log_file, $output);

echo "Diagnostic complete! Output written to diagnostic-output.txt";
?>
