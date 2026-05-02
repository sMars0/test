# Simple PHP Blog

Small test assignment project: a blog website built with pure PHP 8.1, MySQL, PDO, Composer autoloading, and Smarty templates.

## Requirements

- PHP 8.1+
- MySQL 8+
- Composer
- Docker and Docker Compose, optional
- Dart Sass, optional if you want to edit SCSS

## Installation

```bash
composer install
```

Create a MySQL database and configure connection values through environment variables or `config/config.php`.

Import schema:

```bash
mysql -u blog -p blog < database/schema.sql
```

Seed database:

```bash
php database/seed.php
```

Run with PHP built-in server:

```bash
php -S 127.0.0.1:8080 -t public public/index.php
```

Compile SCSS after style changes, optional:

```bash
sass public/assets/scss/style.scss public/assets/css/style.css
```

## Docker

Start containers:

```bash
docker compose up --build
```

Import schema:

```bash
docker compose exec -T mysql mysql -ublog -psecret blog < database/schema.sql
```

Seed database:

```bash
docker compose exec app php database/seed.php
```

Open:

```text
http://localhost:8080
```

## Pages

- `/` - home page with categories and latest articles
- `/category/{id}` - category page with sorting and pagination
- `/category/{id}?sort=date` - sort by publication date
- `/category/{id}?sort=views` - sort by views count
- `/category/{id}?page=2` - category pagination
- `/article/{id}` - article page with view counter and related articles

## Architecture

The project uses a small MVC-like structure without a framework.

- Controllers handle HTTP input and return rendered templates.
- Services contain small application rules, such as pagination, sorting defaults, and article page assembly.
- Repositories contain all SQL queries and use prepared statements.
- Core classes centralize routing, request parsing, PDO connection, and Smarty rendering.
- Templates contain only presentation logic.

Manual dependency injection is done in `public/index.php`.

## AI Usage

AI was used to generate templates, styles, and PHPDocs
