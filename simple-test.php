<?php
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . __DIR__ . "<br>";

// Test if file_exists works
echo "<br>Testing file_exists:<br>";
$test_files = [
    __DIR__ . '/config.php',
    __DIR__ . '/../auth.php',
    __DIR__ . '/../../auth.php',
    '/www/exercices/auth.php',
    '/www/auth.php'
];

foreach ($test_files as $file) {
    $exists = file_exists($file) ? 'EXISTS ✓' : 'NOT FOUND ✗';
    echo "$file => $exists<br>";
}

echo "<br>PDO drivers available:<br>";
print_r(PDO::getAvailableDrivers());
?>
