from flask import Flask, render_template, request, redirect, url_for, flash, jsonify
from database import PromptDatabase

app = Flask(__name__)
app.secret_key = 'votre-cle-secrete-changez-moi'  # Changez cette clé en production

# Initialiser la base de données
db = PromptDatabase()

@app.route('/')
def index():
    """Page d'accueil avec la liste des prompts"""
    category_id = request.args.get('category', type=int)
    search_query = request.args.get('search', '')

    if search_query:
        prompts = db.search_prompts(search_query)
    elif category_id:
        prompts = db.get_all_prompts(category_id=category_id)
    else:
        prompts = db.get_all_prompts()

    categories = db.get_all_categories()
    return render_template('index.html',
                         prompts=prompts,
                         categories=categories,
                         selected_category=category_id,
                         search_query=search_query)

@app.route('/prompt/new', methods=['GET', 'POST'])
def new_prompt():
    """Créer un nouveau prompt"""
    if request.method == 'POST':
        title = request.form.get('title')
        explanation = request.form.get('explanation')
        code = request.form.get('code')
        llm = request.form.get('llm')
        category_id = request.form.get('category_id', type=int)

        if not title or not code:
            flash('Le titre et le code sont obligatoires', 'error')
            return redirect(url_for('new_prompt'))

        db.add_prompt(title, explanation, code, llm, category_id)
        flash('Prompt ajouté avec succès', 'success')
        return redirect(url_for('index'))

    categories = db.get_all_categories()
    return render_template('form.html', categories=categories, prompt=None)

@app.route('/prompt/<int:prompt_id>')
def view_prompt(prompt_id):
    """Voir un prompt spécifique"""
    prompt = db.get_prompt(prompt_id)
    if not prompt:
        flash('Prompt non trouvé', 'error')
        return redirect(url_for('index'))

    return render_template('view.html', prompt=prompt)

@app.route('/prompt/<int:prompt_id>/edit', methods=['GET', 'POST'])
def edit_prompt(prompt_id):
    """Modifier un prompt existant"""
    prompt = db.get_prompt(prompt_id)
    if not prompt:
        flash('Prompt non trouvé', 'error')
        return redirect(url_for('index'))

    if request.method == 'POST':
        title = request.form.get('title')
        explanation = request.form.get('explanation')
        code = request.form.get('code')
        llm = request.form.get('llm')
        category_id = request.form.get('category_id', type=int)

        if not title or not code:
            flash('Le titre et le code sont obligatoires', 'error')
            return redirect(url_for('edit_prompt', prompt_id=prompt_id))

        db.update_prompt(prompt_id, title, explanation, code, llm, category_id)
        flash('Prompt modifié avec succès', 'success')
        return redirect(url_for('view_prompt', prompt_id=prompt_id))

    categories = db.get_all_categories()
    return render_template('form.html', categories=categories, prompt=prompt)

@app.route('/prompt/<int:prompt_id>/delete', methods=['POST'])
def delete_prompt(prompt_id):
    """Supprimer un prompt"""
    db.delete_prompt(prompt_id)
    flash('Prompt supprimé avec succès', 'success')
    return redirect(url_for('index'))

@app.route('/category/new', methods=['POST'])
def new_category():
    """Ajouter une nouvelle catégorie (via AJAX)"""
    name = request.form.get('name')
    if not name:
        return jsonify({'success': False, 'message': 'Le nom est obligatoire'}), 400

    category_id = db.add_category(name)
    if category_id:
        return jsonify({'success': True, 'id': category_id, 'name': name})
    else:
        return jsonify({'success': False, 'message': 'Cette catégorie existe déjà'}), 400

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
