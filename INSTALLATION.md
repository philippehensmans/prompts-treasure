# Installation de Prompts Manager

## ✅ Configuration actuelle

L'application utilise **SQLite** - tout est prêt à fonctionner !

## Problème résolu

Le problème des erreurs 500 venait du fichier `.htaccess` qui utilisait des modules Apache non disponibles sur votre serveur :
- ✗ mod_headers (pour les en-têtes de sécurité)
- ✗ mod_rewrite (pour les règles de réécriture)
- ✗ mod_deflate (pour la compression)
- ✗ mod_expires (pour le cache)

**Solution :** `.htaccess` simplifié avec seulement les protections essentielles.

## Accès à l'application

**URL :** https://www.k1m.be/exercices/prompts/

L'application devrait maintenant fonctionner correctement avec :
- ✓ Base de données SQLite (créée automatiquement)
- ✓ Authentification via `auth.php`
- ✓ Catégories par défaut
- ✓ Protection des fichiers sensibles

## Fichiers protégés par .htaccess

- `config.php` - Bloqué en accès direct
- `*.db`, `*.sqlite`, `*.sqlite3` - Fichiers de base de données protégés
- Listage des répertoires désactivé

## Structure de la base de données

### Table `categories`
- `id` : Identifiant unique
- `name` : Nom de la catégorie

### Table `prompts`
- `id` : Identifiant unique
- `title` : Titre du prompt (obligatoire)
- `explanation` : Explication détaillée
- `code` : Code/texte du prompt (obligatoire)
- `llm` : LLM spécifique (optionnel)
- `category_id` : Catégorie associée
- `suggested_by_email` : Email du suggéreur (optionnel)
- `example_link` : Lien vers un exemple (optionnel)
- `created_at` : Date de création
- `updated_at` : Date de modification

## Fichiers de test (peuvent être supprimés en production)

- `test-sqlite.php` - Vérification SQLite
- `test-basic.php` - Test PHP basique
- `test-html.html` - Test HTML
- `test-paths.php` - Diagnostic chemins
- `diagnostic-to-file.php` - Diagnostic complet
- `phpinfo.php` - Informations PHP
- `simple-test.php` - Test simple

## Nettoyage (optionnel)

Pour supprimer les fichiers de diagnostic :
```bash
rm test-*.php test-*.html diagnostic-*.php diagnostic-*.txt phpinfo.php simple-test.php
```

## Support

Si vous rencontrez des problèmes, vérifiez :
1. Permissions du dossier (lecture/écriture pour créer la base SQLite)
2. Chemin vers `auth.php` dans `includes/header.php`
3. Logs d'erreur Apache pour plus de détails
