# Peer Tutor System

This is a Laravel-based web application for peer tutoring.

## Prerequisites

- PHP >= 8.0
- Composer
- Node.js and npm
- A database server (SQLite by default, or MySQL, PostgreSQL, etc. if configured)

## Setup Instructions

1. Clone the repository:

```bash
git clone https://github.com/Maymay078/Peer-Tutor-Matching-System.git
cd Peer-Tutor-Matching-System
```

2. Install PHP dependencies using Composer:

```bash
composer install
```

3. Install frontend dependencies and build assets:

```bash
npm install
npm run dev
```

4. Copy the example environment file and configure environment variables:

```bash
cp .env.example .env
```

Edit the `.env` file to set your database credentials and update the `APP_URL` to your server's URL or IP address.

By default, the project uses SQLite. If you want to use another database like MySQL or PostgreSQL, update the `DB_CONNECTION` and related settings in `.env`.

**Note on SQLite database file:**

If you are using SQLite, the database is stored in a single file (usually database/database.sqlite). This file must exist before running migrations.

- If the SQLite database file is included in the project, it will contain the current data, and there will be no need to create it again.

- If the database file is not included, an empty SQLite database file must be created at the specified path before running migrations.

- Creating the SQLite database file is straightforward by creating an empty file named database.sqlite in the database directory.

5. Generate the application key:

```bash
php artisan key:generate
```

6. Run database migrations and seeders:

```bash
php artisan migrate --seed
```


7. Serve the application:

For local development, run:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

This will make the app accessible on your local network.

Alternatively, configure your web server (Apache/Nginx) to serve the application.

## Notes

- Ensure your firewall allows incoming connections on the port you use.
- The `.env` file is not included in the repository for security reasons. You must create and configure it manually.
- For production deployment, additional configuration such as caching, queue workers, and SSL setup is recommended.


## License

This project is open-sourced software licensed under the MIT license.
