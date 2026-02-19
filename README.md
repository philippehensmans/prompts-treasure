# Prompts Manager

Application web PHP pour gérer et organiser vos prompts pour les LLM (Large Language Models).

## 🚀 Installation rapide

1. **Téléchargez** `prompts-manager.zip`
2. **Décompressez** le fichier
3. **Uploadez** tous les fichiers sur votre serveur
4. **Accédez** à l'URL de votre application
5. **C'est prêt !** La base de données SQLite est créée automatiquement

## 📖 Documentation

- **[MANUEL.md](MANUEL.md)** : Manuel complet d'utilisation (recommandé)
- **[INSTALLATION.md](INSTALLATION.md)** : Guide d'installation détaillé

## ✨ Fonctionnalités

- ✅ Stockage de prompts avec titre, explication et code
- ✅ Organisation par catégories
- ✅ Vue compacte avec popups au survol
- ✅ Affichage responsive (2 colonnes desktop / 1 colonne mobile)
- ✅ Champs optionnels (LLM, email suggéreur, lien exemple)
- ✅ Authentification intégrée
- ✅ Base SQLite (aucune config requise)
- ✅ Support logo personnalisé
- ✅ Interface moderne et intuitive

## 📋 Prérequis

- Apache 2.4+
- PHP 7.4+ avec PDO_SQLite
- Fichier `auth.php` pour l'authentification

## 📦 Contenu du ZIP

```
prompts-manager/
├── config.php              # Configuration SQLite
├── index.php               # Liste des prompts
├── form.php                # Formulaire ajout/édition
├── view.php                # Vue détaillée
├── delete.php              # Suppression
├── add_category.php        # Ajout catégorie (AJAX)
├── .htaccess               # Sécurité
├── includes/
│   ├── header.php          # En-tête
│   └── footer.php          # Pied de page
├── css/
│   └── style.css           # Styles
├── images/
│   └── README.md           # Instructions logo
├── README.md               # Ce fichier
├── MANUEL.md               # Manuel complet
└── INSTALLATION.md         # Guide installation
```

## 🎯 Démarrage rapide

```bash
# 1. Décompresser
unzip prompts-manager.zip -d prompts

# 2. Placer sur votre serveur
# Uploadez le dossier 'prompts' dans votre répertoire web
# Exemple : /www/exercices/prompts/

# 3. Accéder à l'application
# https://votre-domaine.com/exercices/prompts/
```

### Ajouter un logo (optionnel)

Placez votre logo dans `images/` avec le nom :
- `logo.png` (recommandé)
- `logo.jpg`
- `logo.svg`

Le logo s'affichera automatiquement dans la bannière !

## 🔒 Sécurité

- ✅ Protection des fichiers sensibles via `.htaccess`
- ✅ Requêtes préparées contre l'injection SQL
- ✅ Échappement HTML contre les attaques XSS
- ✅ Intégration avec votre système d'authentification

## 📱 Responsive Design

- Desktop : Affichage sur 2 colonnes
- Mobile : Affichage sur 1 colonne
- Popups adaptées à tous les écrans

## 🛠️ Support

Consultez le **[MANUEL.md](MANUEL.md)** pour :
- Instructions d'installation détaillées
- Guide d'utilisation complet
- Section dépannage
- Personnalisation

## 📝 Version

**Version :** 1.0
**Date :** Décembre 2025
**Technologies :** PHP, SQLite, HTML5, CSS3

---

**Bon usage de Prompts Manager ! 📝**
