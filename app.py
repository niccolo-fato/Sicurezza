from flask import Flask, request, render_template, g
import sqlite3
import os

app = Flask(__name__)
DATABASE = 'test.db'

def get_db():
    db = getattr(g, '_database', None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
    return db

@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()    #lol

def init_db():
    if not os.path.exists(DATABASE):
        with sqlite3.connect(DATABASE) as conn:
            c = conn.cursor()
            c.execute('CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, password TEXT)')
            c.execute('INSERT INTO users (username, password) VALUES ("alice", "alicepass")')
            c.execute('INSERT INTO users (username, password) VALUES ("bob", "bobpass")')
            conn.commit()

@app.route('/', methods=['GET', 'POST'])
def index():
    result = None
    query = ""
    if request.method == 'POST':
        username = request.form['username']
        # VULNERABILITÀ: query costruita direttamente con input utente
        query = f"SELECT * FROM users WHERE username = '{username}'"
        try:
            cur = get_db().cursor()
            cur.execute(query)
            result = cur.fetchall()
        except Exception as e:
            result = str(e)
    return render_template('index.html', result=result, query=query)

if __name__ == '__main__':
    init_db()
    app.run(debug=True) 