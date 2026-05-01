# Foundation and Environment Setup (Days 1-12)

This project is a complete starter for your internship Task 1 goals:
- Professional PHP + MySQL local setup
- HTML5, CSS3, JavaScript fundamentals
- PHP form handling and MySQL CRUD
- Git and GitHub workflow

## 1) Environment Setup (XAMPP / WAMP / LAMP)

### Windows option A: XAMPP
1. Download and install XAMPP from Apache Friends.
2. Open XAMPP Control Panel.
3. Start Apache and MySQL.
4. Place this project folder inside htdocs or run with PHP built-in server.

### Windows option B: WAMP
1. Install WAMP server.
2. Start Apache and MySQL from WAMP tray icon.
3. Open phpMyAdmin from WAMP menu.

### Linux option C: LAMP
1. Install Apache, MySQL, PHP using package manager.
2. Enable and start Apache/MySQL services.
3. Install phpMyAdmin.

## 2) Apache + MySQL + phpMyAdmin

1. Verify Apache: open http://localhost
2. Verify MySQL: run MySQL service and login
3. Open phpMyAdmin: http://localhost/phpmyadmin
4. Import [schema.sql](schema.sql) to create `apex_task1` schema and tables.

## 3) Test PHP Installation

1. Open [hello.php](hello.php)
2. Run one of the following:
- XAMPP/WAMP: `http://localhost/Task%201/hello.php`
- Built-in server: `php -S localhost:8000`
3. Open `http://localhost:8000/hello.php`

## 4) Git and GitHub Setup

1. Initialize repo:
   - `git init`
2. Add files and first commit:
   - `git add .`
   - `git commit -m "Initial portfolio + PHP MySQL foundation"`
3. Create GitHub repo and connect remote:
   - `git remote add origin <your-repo-url>`
4. Push code:
   - `git branch -M main`
   - `git push -u origin main`

## 5) Task 1 Deliverable Checklist

- Personal portfolio website (HTML, CSS, JS)
- Hosted on GitHub Pages (frontend pages)
- At least 10 meaningful commits
- 5-minute demo video covering:
  - page sections and design
  - form validation
  - phpMyAdmin schema
  - PHP form submission and data dashboard

## 6) Feature Mapping to Learning Objectives

### HTML5 Fundamentals
- Semantic structure in [index.html](index.html): `header`, `nav`, `section`, `article`, `footer`
- Forms: text, email, password, select, textarea, radio, checkbox
- Multimedia: audio, video, iframe
- Table: thead, tbody, tfoot

### CSS3 Styling
- Inline CSS: one paragraph in [index.html](index.html)
- Internal CSS: `.badge` in [index.html](index.html)
- External CSS: [style.css](style.css)
- Flexbox/Grid layouts, gradients, shadows, transitions, animation
- Responsive media queries for mobile

### JavaScript Basics
- Variables, loops, arrays, functions in [task.js](task.js)
- DOM APIs: `getElementById`, `querySelector`
- Events: click, keyup, change
- Validation: required fields, email regex, password length

### PHP Basics
- `$_POST` handling in [submit.php](submit.php)
- include/require usage in [submit.php](submit.php), [view.php](view.php), [db.php](db.php)
- control structures and switch in [submit.php](submit.php)

### MySQL and phpMyAdmin
- DB connection with `mysqli_connect()` in [db.php](db.php)
- Tables with PK/FK in [schema.sql](schema.sql)
- INSERT in [submit.php](submit.php)
- SELECT/UPDATE/DELETE in [view.php](view.php)

## 7) Run Locally

1. Ensure MySQL server is running.
2. Start server from project root:
   - `php -S localhost:8000`
3. Open:
   - `http://localhost:8000/index.html`
   - `http://localhost:8000/view.php`

## 8) Suggested Commit Plan (minimum 10)

1. Initialize project structure
2. Add semantic HTML layout
3. Add responsive CSS foundation
4. Add JS events and validation
5. Add PHP hello page
6. Add MySQL connection and schema
7. Add secure form submission (POST)
8. Add submission dashboard (CRUD)
9. Add README and setup instructions
10. Final polish and bug fixes
