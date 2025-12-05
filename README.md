# Prompts Manager

Application web pour stocker et gérer vos prompts utiles pour les LLMs (Large Language Models).

## Fonctionnalités

- **Gestion complète des prompts** : Créer, modifier, supprimer et visualiser vos prompts
- **Organisation par catégories** : Classez vos prompts par catégorie pour un accès facile
- **Recherche** : Recherchez rapidement dans vos prompts par titre, explication ou contenu
- **LLM spécifique** : Indiquez pour quel LLM un prompt est optimisé (GPT-4, Claude, Gemini, etc.)
- **Copie rapide** : Copiez vos prompts en un clic
- **Base de données SQLite** : Stockage local, aucune connexion internet requise

## Structure des fiches

Chaque fiche de prompt contient :

- **Titre** : Nom descriptif du prompt
- **Explication** : Description de l'utilité et du contexte d'utilisation
- **Code (Prompt)** : Le texte du prompt lui-même
- **LLM concerné** : Le modèle de langage spécifique (optionnel)
- **Catégorie** : Classification pour organiser vos prompts

## Installation

### Prérequis

- Python 3.8 ou supérieur
- pip (gestionnaire de paquets Python)

### Étapes d'installation

1. Clonez ou téléchargez ce repository

2. Installez les dépendances :
```bash
pip install -r requirements.txt
```

## Utilisation

1. Lancez l'application :
```bash
python app.py
```

2. Ouvrez votre navigateur et accédez à :
```
http://localhost:5000
```

3. L'application est maintenant accessible !

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
├── app.py                  # Application Flask principale
├── database.py             # Gestion de la base de données SQLite
├── prompts.db              # Base de données (créée automatiquement)
├── requirements.txt        # Dépendances Python
├── templates/              # Templates HTML
│   ├── base.html          # Template de base
│   ├── index.html         # Page d'accueil
│   ├── form.html          # Formulaire création/modification
│   └── view.html          # Vue détaillée d'un prompt
└── static/                # Fichiers statiques
    └── css/
        └── style.css      # Styles CSS
```

## Base de données

L'application utilise SQLite avec deux tables principales :

- **prompts** : Stocke les prompts avec leurs informations
- **categories** : Stocke les catégories de classification

La base de données est créée automatiquement au premier lancement dans le fichier `prompts.db`.

## Personnalisation

### Modifier les catégories par défaut

Éditez le fichier `database.py` et modifiez la liste `default_categories` dans la méthode `init_db()`.

### Changer le port

Dans `app.py`, modifiez la ligne :
```python
app.run(debug=True, host='0.0.0.0', port=5000)
```

### Personnaliser le style

Modifiez le fichier `static/css/style.css` pour adapter les couleurs et le design.

## Sécurité

**Important** : Cette application est conçue pour un usage local. Si vous souhaitez la déployer en production :

1. Changez la clé secrète dans `app.py` :
```python
app.secret_key = 'votre-cle-secrete-changez-moi'
```

2. Désactivez le mode debug :
```python
app.run(debug=False, host='0.0.0.0', port=5000)
```

3. Utilisez un serveur WSGI comme Gunicorn ou uWSGI

## Sauvegarde

Vos prompts sont stockés dans le fichier `prompts.db`. Pour sauvegarder vos données, copiez simplement ce fichier.

## Licence

Libre d'utilisation.

## Support

Pour toute question ou problème, ouvrez une issue sur le repository.
