<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test SQLite</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .ok { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        pre { background: #f0f0f0; padding: 10px; }
    </style>
</head>
<body>
    <h1>Test des extensions SQLite sur ce serveur</h1>

    <h2>1. Version PHP</h2>
    <p><?php echo phpversion(); ?></p>

    <h2>2. Extension PDO</h2>
    <p class="<?php echo extension_loaded('pdo') ? 'ok' : 'error'; ?>">
        PDO: <?php echo extension_loaded('pdo') ? 'DISPONIBLE ✓' : 'NON DISPONIBLE ✗'; ?>
    </p>

    <h2>3. Extension SQLite3</h2>
    <p class="<?php echo extension_loaded('sqlite3') ? 'ok' : 'error'; ?>">
        SQLite3: <?php echo extension_loaded('sqlite3') ? 'DISPONIBLE ✓' : 'NON DISPONIBLE ✗'; ?>
    </p>

    <h2>4. Extension PDO_SQLITE</h2>
    <p class="<?php echo extension_loaded('pdo_sqlite') ? 'ok' : 'error'; ?>">
        PDO_SQLITE: <?php echo extension_loaded('pdo_sqlite') ? 'DISPONIBLE ✓' : 'NON DISPONIBLE ✗'; ?>
    </p>

    <h2>5. Drivers PDO disponibles</h2>
    <pre><?php print_r(PDO::getAvailableDrivers()); ?></pre>

    <h2>6. Test de connexion SQLite</h2>
    <?php
    try {
        $db = new PDO('sqlite::memory:');
        echo '<p class="ok">✓ Connexion SQLite réussie ! SQLite fonctionne sur ce serveur.</p>';
        echo '<p><strong>Conclusion : Vous POUVEZ utiliser SQLite !</strong></p>';

        // Test de création de table
        $db->exec("CREATE TABLE test (id INTEGER PRIMARY KEY, name TEXT)");
        $db->exec("INSERT INTO test (name) VALUES ('test')");
        $result = $db->query("SELECT * FROM test")->fetch();
        echo '<p class="ok">✓ Test d\'écriture/lecture réussi : ' . htmlspecialchars($result['name']) . '</p>';

    } catch(PDOException $e) {
        echo '<p class="error">✗ Erreur SQLite : ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Conclusion : SQLite n\'est PAS disponible. MySQL est nécessaire.</strong></p>';
    }
    ?>

    <hr>
    <p><em>Fichier : <?php echo __FILE__; ?></em></p>
</body>
</html>
