# Prompts Manager

Application web PHP pour stocker et gérer vos prompts utiles pour les LLMs (Large Language Models).

## Fonctionnalités

- **Gestion complète des prompts** : Créer, modifier, supprimer et visualiser vos prompts
- **Organisation par catégories** : Classez vos prompts par catégorie pour un accès facile
- **Recherche** : Recherchez rapidement dans vos prompts par titre, explication ou contenu
- **LLM spécifique** : Indiquez pour quel LLM un prompt est optimisé (GPT-4, Claude, Gemini, etc.)
- **Copie rapide** : Copiez vos prompts en un clic
- **Base de données SQLite** : Stockage local, aucune connexion internet requise
- **Intégration auth.php** : Compatible avec votre système d'authentification existant

## Structure des fiches

Chaque fiche de prompt contient :

- **Titre** : Nom descriptif du prompt
- **Explication** : Description de l'utilité et du contexte d'utilisation
- **Code (Prompt)** : Le texte du prompt lui-même
- **LLM concerné** : Le modèle de langage spécifique (optionnel)
- **Catégorie** : Classification pour organiser vos prompts

## Prérequis

- PHP 7.4 ou supérieur avec PDO et l'extension SQLite3
- Serveur web (Apache, Nginx, etc.) ou PHP built-in server
- Un fichier `auth.php` dans le répertoire parent (pour l'authentification)

## Installation

1. Clonez ou téléchargez ce repository dans votre serveur web

2. Assurez-vous que le fichier `auth.php` existe dans le répertoire parent :
```
/votre-site/
    auth.php         <- Votre fichier d'authentification
    prompts-treasure/
        index.php
        config.php
        ...
```

3. Configurez les permissions pour que PHP puisse créer la base de données :
```bash
chmod 755 /chemin/vers/prompts-treasure
```

4. Accédez à l'application via votre navigateur :
```
http://votre-site.com/prompts-treasure/
```

La base de données SQLite sera créée automatiquement au premier accès.

## Utilisation avec le serveur PHP intégré (développement)

Pour tester localement :

```bash
cd prompts-treasure
php -S localhost:8000
```

Puis ouvrez http://localhost:8000 dans votre navigateur.

**Note** : Pour le développement, vous devrez créer un fichier `auth.php` temporaire dans le répertoire parent si vous n'en avez pas. Exemple minimal :
```php
<?php
// auth.php minimal pour développement
session_start();
// Ajoutez votre logique d'authentification ici
?>
```

## Guide d'utilisation

### Créer un nouveau prompt

1. Cliquez sur le bouton "Nouveau prompt" dans la barre de navigation
2. Remplissez les champs du formulaire :
   - **Titre** (obligatoire) : Donnez un nom à votre prompt
   - **Explication** : Décrivez son utilité et quand l'utiliser
   - **Code** (obligatoire) : Collez votre prompt ici
   - **LLM spécifique** : Indiquez le LLM concerné (ex: GPT-4, Claude 3)
   - **Catégorie** : Sélectionnez une catégorie existante ou créez-en une nouvelle
3. Cliquez sur "Créer"

### Parcourir les prompts

- La page d'accueil affiche tous vos prompts sous forme de cartes
- Utilisez les filtres par catégorie en haut de la page
- Utilisez la barre de recherche pour trouver des prompts spécifiques

### Voir un prompt en détail

- Cliquez sur le bouton "Voir" d'une carte de prompt
- Vous verrez tous les détails du prompt
- Utilisez le bouton "Copier" pour copier le prompt dans votre presse-papier

### Modifier un prompt

- Depuis la vue détaillée, cliquez sur "Modifier"
- Ou cliquez sur "Modifier" directement depuis la carte du prompt
- Modifiez les champs souhaités et cliquez sur "Mettre à jour"

### Supprimer un prompt

- Depuis la vue détaillée, cliquez sur "Supprimer"
- Confirmez la suppression

### Gérer les catégories

- Lors de la création ou modification d'un prompt, vous pouvez ajouter une nouvelle catégorie
- Cliquez sur "Nouvelle catégorie" dans le formulaire
- Les catégories par défaut incluent : Génération de code, Analyse de données, Rédaction, Traduction, Débogage, Documentation, Autre

## Structure du projet

```
prompts-treasure/
├── index.php               # Page d'accueil avec liste des prompts
├── form.php               # Formulaire création/modification
├── view.php               # Vue détaillée d'un prompt
├── delete.php             # Suppression d'un prompt
├── add_category.php       # API pour ajouter une catégorie (AJAX)
├── config.php             # Configuration et initialisation de la DB
├── prompts.db             # Base de données SQLite (créée automatiquement)
├── includes/
│   ├── header.php        # En-tête avec inclusion auth.php
│   └── footer.php        # Pied de page
└── css/
    └── style.css         # Styles CSS
```

## Base de données

L'application utilise SQLite avec deux tables principales :

- **prompts** : Stocke les prompts avec leurs informations
  - id (INTEGER PRIMARY KEY)
  - title (TEXT NOT NULL)
  - explanation (TEXT)
  - code (TEXT NOT NULL)
  - llm (TEXT)
  - category_id (INTEGER)
  - created_at (DATETIME)
  - updated_at (DATETIME)

- **categories** : Stocke les catégories de classification
  - id (INTEGER PRIMARY KEY)
  - name (TEXT NOT NULL UNIQUE)

La base de données est créée automatiquement au premier lancement dans le fichier `prompts.db`.

## Authentification

L'application inclut automatiquement le fichier `../auth.php` dans chaque page via `includes/header.php`.

Structure attendue :
```
/votre-repertoire-parent/
    auth.php                    <- Votre système d'authentification
    prompts-treasure/
        index.php
        form.php
        ...
```

Si vous devez modifier le chemin vers `auth.php`, éditez le fichier `includes/header.php` :
```php
include '../auth.php';  // Modifiez ce chemin si nécessaire
```

## Personnalisation

### Modifier les catégories par défaut

Éditez le fichier `config.php` et modifiez le tableau `$default_categories` dans la fonction `initDB()`.

### Personnaliser le style

Modifiez le fichier `css/style.css` pour adapter les couleurs et le design.

### Modifier le chemin de la base de données

Dans `config.php`, changez la constante :
```php
define('DB_PATH', __DIR__ . '/prompts.db');
```

## Sécurité

- L'application utilise PDO avec des requêtes préparées pour prévenir les injections SQL
- Toutes les sorties HTML sont échappées avec `htmlspecialchars()`
- Les sessions PHP sont utilisées pour les messages flash
- L'authentification est gérée par votre fichier `auth.php`

## Sauvegarde

Vos prompts sont stockés dans le fichier `prompts.db`. Pour sauvegarder vos données, copiez simplement ce fichier.

```bash
cp prompts.db prompts.db.backup
```

## Dépannage

### Erreur de permissions

Si vous obtenez une erreur lors de la création de la base de données :
```bash
chmod 755 /chemin/vers/prompts-treasure
```

### La base de données n'est pas créée

Vérifiez que l'extension SQLite3 est activée dans PHP :
```bash
php -m | grep -i sqlite
```

### Erreur avec auth.php

Si vous obtenez une erreur "Failed to open stream: auth.php", assurez-vous que le fichier existe dans le répertoire parent ou modifiez le chemin dans `includes/header.php`.

## Configuration du serveur web

### Apache

Ajoutez un fichier `.htaccess` (optionnel) :
```apache
RewriteEngine On
DirectoryIndex index.php
```

### Nginx

Configuration de base :
```nginx
location /prompts-treasure {
    index index.php;
    try_files $uri $uri/ /index.php?$query_string;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

## Technologies utilisées

- PHP 7.4+
- SQLite3 via PDO
- HTML5 / CSS3
- JavaScript (Vanilla) pour les interactions AJAX

## Licence

Libre d'utilisation.

## Support

Pour toute question ou problème, ouvrez une issue sur le repository.
