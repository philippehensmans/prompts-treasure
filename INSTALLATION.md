# Installation de Prompts Manager

## Problème identifié ❌

Votre serveur **ne dispose pas de l'extension PDO_SQLite**. C'est pour cela que vous avez des erreurs 500.

**Extensions disponibles sur votre serveur:**
- ✓ PDO
- ✓ PDO_MySQL
- ✓ PDO_PostgreSQL
- ✗ SQLite3
- ✗ PDO_SQLite

## Solution : Utiliser MySQL

### Étape 1 : Créer une base de données MySQL

1. Connectez-vous à votre panneau d'hébergement (cPanel, Plesk, ou phpMyAdmin)
2. Créez une nouvelle base de données MySQL nommée `prompts_db` (ou un autre nom de votre choix)
3. Créez un utilisateur MySQL avec tous les privilèges sur cette base
4. Notez les informations de connexion :
   - Nom de la base
   - Nom d'utilisateur
   - Mot de passe
   - Hôte (généralement `localhost`)

### Étape 2 : Configurer l'application

1. Ouvrez le fichier `config.php`
2. Modifiez les lignes 7-10 avec vos informations MySQL :

```php
define('DB_HOST', 'localhost');          // Votre hôte MySQL
define('DB_NAME', 'prompts_db');         // Nom de votre base
define('DB_USER', 'votre_utilisateur');  // Votre utilisateur MySQL
define('DB_PASS', 'votre_mot_de_passe'); // Votre mot de passe MySQL
```

### Étape 3 : Tester l'installation

1. Accédez à votre application : `https://www.k1m.be/exercices/prompts/`
2. Les tables seront créées automatiquement
3. Les catégories par défaut seront ajoutées

## Fichiers modifiés

J'ai déjà effectué les modifications suivantes :

1. ✓ **includes/header.php** : Correction du chemin vers `auth.php` (de `/../..` vers `/..`)
2. ✓ **config.php** : Prêt pour MySQL (vous devez juste ajouter vos identifiants)
3. ✓ Sauvegarde de l'ancien config SQLite dans `config-sqlite-backup.php`

## Si vous avez accès SSH au serveur

Si vous pouvez installer des extensions PHP, vous pouvez installer PDO_SQLite :

### Ubuntu/Debian :
```bash
sudo apt-get install php-sqlite3
sudo systemctl restart apache2
```

### CentOS/RHEL :
```bash
sudo yum install php-pdo-sqlite
sudo systemctl restart httpd
```

Ensuite, restaurez l'ancien config :
```bash
cp config-sqlite-backup.php config.php
```

## Besoin d'aide ?

Si vous rencontrez des problèmes, vérifiez :
- Que les identifiants MySQL sont corrects
- Que l'utilisateur MySQL a tous les privilèges sur la base
- Les logs d'erreur Apache/PHP pour plus de détails
