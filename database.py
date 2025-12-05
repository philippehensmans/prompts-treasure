import sqlite3
from datetime import datetime

class PromptDatabase:
    def __init__(self, db_name='prompts.db'):
        self.db_name = db_name
        self.init_db()

    def get_connection(self):
        """Créer une connexion à la base de données"""
        conn = sqlite3.connect(self.db_name)
        conn.row_factory = sqlite3.Row
        return conn

    def init_db(self):
        """Initialiser la base de données avec les tables nécessaires"""
        conn = self.get_connection()
        cursor = conn.cursor()

        # Table des catégories
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE
            )
        ''')

        # Table des prompts
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS prompts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                explanation TEXT,
                code TEXT NOT NULL,
                llm TEXT,
                category_id INTEGER,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )
        ''')

        # Ajouter des catégories par défaut si la table est vide
        cursor.execute('SELECT COUNT(*) as count FROM categories')
        if cursor.fetchone()['count'] == 0:
            default_categories = [
                'Génération de code',
                'Analyse de données',
                'Rédaction',
                'Traduction',
                'Débogage',
                'Documentation',
                'Autre'
            ]
            cursor.executemany('INSERT INTO categories (name) VALUES (?)',
                             [(cat,) for cat in default_categories])

        conn.commit()
        conn.close()

    def add_prompt(self, title, explanation, code, llm, category_id):
        """Ajouter un nouveau prompt"""
        conn = self.get_connection()
        cursor = conn.cursor()
        cursor.execute('''
            INSERT INTO prompts (title, explanation, code, llm, category_id)
            VALUES (?, ?, ?, ?, ?)
        ''', (title, explanation, code, llm, category_id))
        conn.commit()
        prompt_id = cursor.lastrowid
        conn.close()
        return prompt_id

    def get_all_prompts(self, category_id=None):
        """Récupérer tous les prompts, optionnellement filtrés par catégorie"""
        conn = self.get_connection()
        cursor = conn.cursor()

        if category_id:
            cursor.execute('''
                SELECT p.*, c.name as category_name
                FROM prompts p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = ?
                ORDER BY p.created_at DESC
            ''', (category_id,))
        else:
            cursor.execute('''
                SELECT p.*, c.name as category_name
                FROM prompts p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC
            ''')

        prompts = cursor.fetchall()
        conn.close()
        return prompts

    def get_prompt(self, prompt_id):
        """Récupérer un prompt spécifique"""
        conn = self.get_connection()
        cursor = conn.cursor()
        cursor.execute('''
            SELECT p.*, c.name as category_name
            FROM prompts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ''', (prompt_id,))
        prompt = cursor.fetchone()
        conn.close()
        return prompt

    def update_prompt(self, prompt_id, title, explanation, code, llm, category_id):
        """Mettre à jour un prompt existant"""
        conn = self.get_connection()
        cursor = conn.cursor()
        cursor.execute('''
            UPDATE prompts
            SET title = ?, explanation = ?, code = ?, llm = ?,
                category_id = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ''', (title, explanation, code, llm, category_id, prompt_id))
        conn.commit()
        conn.close()

    def delete_prompt(self, prompt_id):
        """Supprimer un prompt"""
        conn = self.get_connection()
        cursor = conn.cursor()
        cursor.execute('DELETE FROM prompts WHERE id = ?', (prompt_id,))
        conn.commit()
        conn.close()

    def get_all_categories(self):
        """Récupérer toutes les catégories"""
        conn = self.get_connection()
        cursor = conn.cursor()
        cursor.execute('SELECT * FROM categories ORDER BY name')
        categories = cursor.fetchall()
        conn.close()
        return categories

    def add_category(self, name):
        """Ajouter une nouvelle catégorie"""
        conn = self.get_connection()
        cursor = conn.cursor()
        try:
            cursor.execute('INSERT INTO categories (name) VALUES (?)', (name,))
            conn.commit()
            category_id = cursor.lastrowid
            conn.close()
            return category_id
        except sqlite3.IntegrityError:
            conn.close()
            return None

    def search_prompts(self, query):
        """Rechercher des prompts par titre ou explication"""
        conn = self.get_connection()
        cursor = conn.cursor()
        cursor.execute('''
            SELECT p.*, c.name as category_name
            FROM prompts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.title LIKE ? OR p.explanation LIKE ? OR p.code LIKE ?
            ORDER BY p.created_at DESC
        ''', (f'%{query}%', f'%{query}%', f'%{query}%'))
        prompts = cursor.fetchall()
        conn.close()
        return prompts
