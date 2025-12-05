<?php
// Fichier de test pour diagnostiquer les chemins
echo "<h1>Test des chemins</h1>";

echo "<h2>Informations PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "__DIR__: " . __DIR__ . "<br>";
echo "__FILE__: " . __FILE__ . "<br>";

echo "<h2>Chemins testés</h2>";

// Test 1: Chemin actuel vers auth.php
$path1 = __DIR__ . '/../auth.php';
echo "Path 1 (depuis racine): $path1<br>";
echo "Existe: " . (file_exists($path1) ? 'OUI ✅' : 'NON ❌') . "<br><br>";

// Test 2: Chemin depuis includes
$path2 = __DIR__ . '/includes/../auth.php';
echo "Path 2: $path2<br>";
echo "Existe: " . (file_exists($path2) ? 'OUI ✅' : 'NON ❌') . "<br><br>";

// Test 3: Chemin absolu possible
$path3 = '/www/exercices/auth.php';
echo "Path 3 (absolu /www/exercices): $path3<br>";
echo "Existe: " . (file_exists($path3) ? 'OUI ✅' : 'NON ❌') . "<br><br>";

// Test 4: Autre chemin absolu possible
$path4 = '/www/auth.php';
echo "Path 4 (absolu /www): $path4<br>";
echo "Existe: " . (file_exists($path4) ? 'OUI ✅' : 'NON ❌') . "<br><br>";

echo "<h2>Test config.php</h2>";
$configPath = __DIR__ . '/config.php';
echo "Config path: $configPath<br>";
echo "Existe: " . (file_exists($configPath) ? 'OUI ✅' : 'NON ❌') . "<br><br>";

echo "<h2>Liste des fichiers dans le répertoire parent</h2>";
$parentDir = dirname(__DIR__);
echo "Répertoire parent: $parentDir<br>";
if (is_dir($parentDir)) {
    $files = scandir($parentDir);
    echo "<pre>";
    print_r($files);
    echo "</pre>";
}

echo "<h2>Extensions PHP chargées</h2>";
echo "PDO: " . (extension_loaded('pdo') ? 'OUI ✅' : 'NON ❌') . "<br>";
echo "SQLite3: " . (extension_loaded('sqlite3') ? 'OUI ✅' : 'NON ❌') . "<br>";
echo "PDO_SQLITE: " . (extension_loaded('pdo_sqlite') ? 'OUI ✅' : 'NON ❌') . "<br>";
?>
