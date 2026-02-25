from flask import Flask, render_template_string, request, redirect, url_for, flash, session
import sqlite3
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = 'your_secret_key'  # Required for session management

# Database setup
DATABASE = 'alumni.db'

def init_db():
    with sqlite3.connect(DATABASE) as conn:
        cursor = conn.cursor()
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL
            )
        ''')
        conn.commit()

# Initialize the database
init_db()

# Home page
@app.route('/')
def home():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Alumni Management System</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background: linear-gradient(135deg, #ff7e5f, #feb47b, #56ccf2, #6a11cb);
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                header {
                    padding: 50px 20px;
                    color: #fff;
                    text-align: center;
                    background: linear-gradient(90deg, #ff6f61, #d6336c, #8a2be2);
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                }
                header h1, header p {
                    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
                }
                .info-section {
                    padding: 20px;
                    text-align: center;
                }
                .info-section h2 {
                    margin-bottom: 20px;
                    color: #ff6f61;
                    font-weight: bold;
                }
                .info-section p {
                    font-size: 1.5em;
                    font-weight: bold;
                }
                nav {
                    background: #333;
                    padding: 10px 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-wrap: wrap;
                }
                nav a {
                    color: #fff;
                    text-decoration: none;
                    margin: 0 15px;
                    font-size: 1.2em;
                    font-weight: bold;
                }
                nav a:hover {
                    color: #feb47b;
                }
                .image-gallery {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 15px;
                    justify-content: center;
                    padding: 20px;
                }
                .image-gallery img {
                    width: 100%;
                    max-width: 200px;
                    border-radius: 10px;
                    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
                }
                footer {
                    background: linear-gradient(90deg, #8a2be2, #d6336c, #ff6f61);
                    color: #fff;
                    text-align: center;
                    padding: 20px;
                    box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.3);
                }
            </style>
        </head>
        <body>
            <header>
                <h1>Alumni Management System</h1>
            </header>

            <div class="info-section">
                <h2>About the Alumni Management System</h2>
                <p>
                    The Alumni Management System of JNTUA is designed to foster connections between alumni and their alma mater. 
                    It provides a platform for networking, career growth, and collaboration among alumni, students, and the university. 
                    This system serves as a bridge to share achievements, offer mentorship, and promote lifelong engagement with the university community.
                </p>
                <p>
                    Join us to stay updated on alumni events, contribute to institutional development, and celebrate your achievements with a global network of peers.
                </p>
            </div>

            <nav>
                <a href="/">Home</a>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
                <a href="/events">Events</a>
                <a href="/jobs">Jobs</a>
                <a href="/mentorship">Mentorship</a>
                <a href="/gallery">Gallery</a>
                <a href="/contact">Contact Us</a>
                <a href="/profile">Profile</a>
                <a href="/videos">Videos</a>
                <a href="/donate">Donate</a>
            </nav>

            <div class="image-gallery">
                <img src="https://via.placeholder.com/200" alt="JNTUA Building">
                <img src="https://via.placeholder.com/200" alt="JNTUA Campus">
                <img src="https://via.placeholder.com/200" alt="JNTUA Event">
                <img src="https://via.placeholder.com/200" alt="JNTUA Alumni Meet">
            </div>

            <footer>
                <p>&copy; 2025 Alumni Management System. All rights reserved.</p>
            </footer>
        </body>
        </html>
    ''')

# Login page
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        with sqlite3.connect(DATABASE) as conn:
            cursor = conn.cursor()
            cursor.execute('SELECT * FROM users WHERE username = ?', (username,))
            user = cursor.fetchone()
            if user and check_password_hash(user[3], password):  # Check hashed password
                session['user_id'] = user[0]  # Store user ID in session
                flash('Login successful!', 'success')
                return redirect(url_for('profile'))
            else:
                flash('Invalid username or password', 'error')
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Login</h1>
            <form method="POST">
                <label>Username:</label>
                <input type="text" name="username" required><br>
                <label>Password:</label>
                <input type="password" name="password" required><br>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="/register">Register here</a></p>
        </body>
        </html>
    ''')

# Registration page
@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['username']
        email = request.form['email']
        password = generate_password_hash(request.form['password'])  # Hash the password
        with sqlite3.connect(DATABASE) as conn:
            cursor = conn.cursor()
            try:
                cursor.execute('INSERT INTO users (username, email, password) VALUES (?, ?, ?)', (username, email, password))
                conn.commit()
                flash('Registration successful! Please login.', 'success')
                return redirect(url_for('login'))
            except sqlite3.IntegrityError:
                flash('Username or email already exists', 'error')
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Register</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Register</h1>
            <form method="POST">
                <label>Username:</label>
                <input type="text" name="username" required><br>
                <label>Email:</label>
                <input type="email" name="email" required><br>
                <label>Password:</label>
                <input type="password" name="password" required><br>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="/login">Login here</a></p>
        </body>
        </html>
    ''')

# Profile page
@app.route('/profile')
def profile():
    if 'user_id' not in session:
        flash('Please login to view your profile', 'error')
        return redirect(url_for('login'))
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Profile</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Profile</h1>
            <p>Welcome to your profile!</p>
            <a href="/">Home</a>
            <a href="/logout">Logout</a>
        </body>
        </html>
    ''')

# Logout
@app.route('/logout')
def logout():
    session.pop('user_id', None)  # Remove user ID from session
    flash('You have been logged out', 'success')
    return redirect(url_for('home'))

# Other pages
@app.route('/events')
def events():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Events</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Events</h1>
            <p>Check out our upcoming events!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

@app.route('/jobs')
def jobs():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Jobs</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Jobs</h1>
            <p>Find job opportunities here!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

@app.route('/mentorship')
def mentorship():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Mentorship</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Mentorship</h1>
            <p>Connect with mentors and mentees!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

@app.route('/gallery')
def gallery():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Gallery</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Gallery</h1>
            <p>View photos from our events!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

@app.route('/contact')
def contact():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Contact Us</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Contact Us</h1>
            <p>Reach out to us for any queries!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

@app.route('/videos')
def videos():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Videos</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Videos</h1>
            <p>Watch our latest videos!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

@app.route('/donate')
def donate():
    return render_template_string('''
        <!DOCTYPE html>
        <html>
        <head>
            <title>Donate</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>
        <body>
            <h1>Donate</h1>
            <p>Support our initiatives!</p>
            <a href="/">Home</a>
        </body>
        </html>
    ''')

if __name__ == '__main__':
    app.run(debug=True)