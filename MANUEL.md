# Manuel d'utilisation - Prompts Manager

## Table des matières

1. [Présentation](#présentation)
2. [Prérequis](#prérequis)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Utilisation](#utilisation)
6. [Personnalisation](#personnalisation)
7. [Dépannage](#dépannage)
8. [Structure des fichiers](#structure-des-fichiers)

---

## Présentation

**Prompts Manager** est une application web PHP permettant de stocker, organiser et gérer vos prompts pour les LLM (Large Language Models).

### Fonctionnalités principales

- ✅ **Stockage de prompts** avec titre, explication et code
- ✅ **Organisation par catégories** avec gestion dynamique
- ✅ **Vue compacte** : liste des titres organisés par catégories
- ✅ **Popups au survol** : affichage du contenu complet en survolant un prompt
- ✅ **Affichage responsive** : 2 colonnes sur desktop, 1 colonne sur mobile
- ✅ **Champs optionnels** : LLM spécifique, email du suggéreur, lien exemple
- ✅ **Authentification** : intégration avec votre système auth.php existant
- ✅ **Base de données SQLite** : aucune configuration serveur nécessaire
- ✅ **Interface moderne** : design épuré et intuitif

---

## Prérequis

### Serveur web

- **Apache** 2.4+
- **PHP** 7.4+ avec les extensions :
  - PDO
  - PDO_SQLite
  - SQLite3 (recommandé)
- **Permissions** : lecture/écriture dans le dossier de l'application

### Fichiers requis

- Un fichier `auth.php` dans le répertoire parent (pour l'authentification)
- Accès FTP/SFTP ou panneau d'hébergement pour uploader les fichiers

---

## Installation

### Étape 1 : Télécharger l'application

1. Téléchargez le fichier `prompts-manager.zip`
2. Décompressez-le sur votre ordinateur

### Étape 2 : Vérifier la structure

Assurez-vous d'avoir cette structure après décompression :

```
prompts-manager/
├── config.php
├── index.php
├── form.php
├── view.php
├── delete.php
├── add_category.php
├── .htaccess
├── includes/
│   ├── header.php
│   └── footer.php
├── css/
│   └── style.css
├── images/
│   └── README.md
├── MANUEL.md
└── INSTALLATION.md
```

### Étape 3 : Uploader sur votre serveur

Uploadez tous les fichiers dans le dossier souhaité sur votre serveur.

**Exemple :**
```
/www/exercices/prompts/
```

### Étape 4 : Vérifier l'emplacement de auth.php

L'application cherche le fichier `auth.php` dans le répertoire parent.

**Structure attendue :**
```
/www/exercices/
├── auth.php              ← Votre fichier d'authentification
└── prompts/
    ├── index.php
    ├── config.php
    └── ...
```

Si votre `auth.php` est ailleurs, modifiez la ligne 3 de `includes/header.php` :

```php
// Ligne actuelle :
include __DIR__ . '/../auth.php';

// Si auth.php est 2 niveaux au-dessus :
include __DIR__ . '/../../auth.php';

// Si auth.php est à un chemin absolu :
include '/chemin/absolu/vers/auth.php';
```

### Étape 5 : Vérifier les permissions

Le dossier de l'application doit avoir les permissions d'écriture pour créer la base de données SQLite.

```bash
chmod 755 /www/exercices/prompts/
chmod 644 /www/exercices/prompts/*.php
```

### Étape 6 : Accéder à l'application

Ouvrez votre navigateur et accédez à :
```
https://votre-domaine.com/exercices/prompts/
```

Au premier accès :
- ✅ La base de données SQLite sera créée automatiquement (`prompts.db`)
- ✅ Les catégories par défaut seront ajoutées
- ✅ L'application est prête à l'emploi !

---

## Configuration

### Configuration de la base de données

Le fichier `config.php` contient la configuration SQLite. **Aucune modification n'est nécessaire** pour un usage standard.

```php
define('DB_PATH', __DIR__ . '/prompts.db');
```

### Catégories par défaut

Les catégories suivantes sont créées automatiquement :
- Génération de code
- Analyse de données
- Rédaction
- Traduction
- Débogage
- Documentation
- Autre

Vous pouvez ajouter de nouvelles catégories directement depuis le formulaire.

### Protection des fichiers

Le fichier `.htaccess` protège automatiquement :
- `config.php` : bloqué en accès direct
- `*.db`, `*.sqlite`, `*.sqlite3` : fichiers de base de données protégés
- Listage des répertoires désactivé

---

## Utilisation

### Ajouter un prompt

1. Cliquez sur **"Nouveau prompt"** dans le menu
2. Remplissez les champs :
   - **Titre*** : Nom du prompt (obligatoire)
   - **Explication** : Description détaillée
   - **Code/Prompt*** : Contenu du prompt (obligatoire)
   - **LLM spécifique** : Ex: "Claude", "GPT-4", etc. (optionnel)
   - **Catégorie** : Sélectionnez ou créez une catégorie
   - **Email suggéreur** : Email de la personne qui a suggéré ce prompt (optionnel)
   - **Lien exemple** : URL vers un exemple d'utilisation (optionnel)
3. Cliquez sur **"Enregistrer"**

### Voir la liste des prompts

La page d'accueil affiche :
- **Organisation par catégories** : Les prompts sont regroupés par catégorie
- **Affichage sur 2 colonnes** (desktop) ou 1 colonne (mobile)
- **Titres seulement** : Seuls les noms des prompts sont visibles

**Au survol d'un prompt** : Une popup s'affiche avec :
- Titre complet
- Explication
- LLM spécifique (si renseigné)
- Code/prompt complet
- Email du suggéreur (avec lien mailto)
- Lien exemple (ouvre dans un nouvel onglet)
- Date de création

### Modifier un prompt

1. Survolez un prompt dans la liste
2. Cliquez sur l'icône **✏️** (modifier)
3. Modifiez les informations
4. Cliquez sur **"Enregistrer"**

### Supprimer un prompt

1. Cliquez sur un prompt pour voir les détails
2. Cliquez sur **"Supprimer"**
3. Confirmez la suppression

### Voir les détails d'un prompt

1. Cliquez sur le titre d'un prompt dans la liste
2. La page détaillée affiche :
   - Titre et catégorie
   - Explication complète
   - LLM spécifique
   - Code/prompt avec bouton **"Copier"**
   - Email du suggéreur (cliquable)
   - Lien exemple
   - Dates de création et modification
   - Boutons **Modifier** et **Supprimer**

### Ajouter une catégorie

Depuis le formulaire d'ajout/édition :
1. Dans le champ "Catégorie", cliquez sur **"+ Nouvelle catégorie"**
2. Entrez le nom de la nouvelle catégorie
3. Cliquez sur **"Ajouter"**
4. La nouvelle catégorie est automatiquement sélectionnée

---

## Personnalisation

### Ajouter un logo

1. Préparez votre logo :
   - **Format recommandé :** PNG avec fond transparent
   - **Dimensions :** Hauteur de 40-80px (largeur auto)
   - **Poids :** < 100 Ko

2. Uploadez votre logo dans le dossier `images/` avec le nom :
   - `logo.png` (recommandé)
   - `logo.jpg`
   - `logo.svg`

3. Rafraîchissez la page : le logo s'affiche automatiquement !

**Sans logo :** L'icône 📝 est affichée par défaut

### Modifier les couleurs

Éditez le fichier `css/style.css` et modifiez les variables CSS (lignes 2-13) :

```css
:root {
    --primary-color: #4a6fa5;      /* Couleur principale */
    --secondary-color: #6c757d;    /* Couleur secondaire */
    --success-color: #28a745;      /* Vert de succès */
    --danger-color: #dc3545;       /* Rouge de danger */
    --light-bg: #f8f9fa;           /* Fond clair */
    --border-color: #dee2e6;       /* Couleur des bordures */
    --text-dark: #212529;          /* Texte foncé */
    --text-muted: #6c757d;         /* Texte grisé */
}
```

### Modifier le titre de l'application

Éditez `includes/header.php` à la ligne 35 :

```php
<span>Prompts Manager</span>  <!-- Changez ce texte -->
```

---

## Dépannage

### Erreur 500 - Internal Server Error

**Cause 1 : Chemin incorrect vers auth.php**

Solution : Vérifiez le chemin dans `includes/header.php` ligne 3

**Cause 2 : Modules Apache manquants**

Si vous avez modifié `.htaccess`, certains modules peuvent être requis :
- `mod_headers`
- `mod_rewrite`
- `mod_deflate`
- `mod_expires`

Solution : Utilisez le `.htaccess` fourni qui ne requiert que les modules de base.

**Cause 3 : Permissions insuffisantes**

Solution : Vérifiez que le dossier a les permissions d'écriture.

### Base de données non créée

**Symptôme :** L'application affiche des erreurs de connexion

**Solution :**
1. Vérifiez les permissions du dossier (doit pouvoir écrire)
2. Vérifiez que l'extension PDO_SQLite est installée
3. Consultez les logs d'erreur PHP

### Les popups ne s'affichent pas

**Solution :**
1. Vérifiez que JavaScript n'est pas bloqué
2. Vérifiez que le CSS est bien chargé (`css/style.css`)
3. Testez sur un autre navigateur

### Le logo ne s'affiche pas

**Solution :**
1. Vérifiez que le fichier est bien nommé `logo.png`, `logo.jpg` ou `logo.svg`
2. Vérifiez que le fichier est dans le dossier `images/`
3. Vérifiez les permissions du fichier (doit être lisible)
4. Videz le cache du navigateur

---

## Structure des fichiers

### Fichiers principaux

| Fichier | Description |
|---------|-------------|
| `index.php` | Page d'accueil - liste des prompts par catégories |
| `form.php` | Formulaire d'ajout/édition de prompts |
| `view.php` | Vue détaillée d'un prompt |
| `delete.php` | Suppression d'un prompt |
| `add_category.php` | Endpoint AJAX pour ajouter une catégorie |
| `config.php` | Configuration de la base de données |
| `.htaccess` | Configuration Apache et sécurité |

### Répertoires

| Dossier | Contenu |
|---------|---------|
| `includes/` | Fichiers d'en-tête et pied de page |
| `css/` | Feuilles de style |
| `images/` | Logo et images |

### Base de données

**Fichier :** `prompts.db` (créé automatiquement)

**Table `categories` :**
- `id` : Identifiant unique (auto-incrémenté)
- `name` : Nom de la catégorie (unique)

**Table `prompts` :**
- `id` : Identifiant unique (auto-incrémenté)
- `title` : Titre du prompt
- `explanation` : Explication détaillée
- `code` : Code/texte du prompt
- `llm` : LLM spécifique (optionnel)
- `category_id` : Référence à la catégorie
- `suggested_by_email` : Email du suggéreur (optionnel)
- `example_link` : Lien vers un exemple (optionnel)
- `created_at` : Date de création
- `updated_at` : Date de dernière modification

---

## Sécurité

### Protections intégrées

✅ **Authentification** : Intégration avec votre système auth.php
✅ **Injection SQL** : Utilisation de requêtes préparées (PDO)
✅ **XSS** : Échappement HTML avec `htmlspecialchars()`
✅ **Fichiers sensibles** : Protection via `.htaccess`
✅ **Base de données** : Fichiers `.db` bloqués en accès direct

### Recommandations

- Gardez PHP à jour (7.4+ recommandé, 8.0+ optimal)
- Utilisez HTTPS pour votre site
- Sauvegardez régulièrement `prompts.db`
- Ne partagez jamais votre fichier `config.php`

---

## Support et contact

Pour toute question ou problème :

1. Consultez d'abord ce manuel
2. Vérifiez la section [Dépannage](#dépannage)
3. Consultez les logs d'erreur Apache/PHP
4. Vérifiez que tous les prérequis sont remplis

---

## Licence et crédits

Application développée pour gérer efficacement vos prompts LLM.

**Version :** 1.0
**Date :** Décembre 2025
**Technologies :** PHP, SQLite, HTML5, CSS3

---

**Bon usage de Prompts Manager ! 📝**
