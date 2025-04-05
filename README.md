# music-app
 
## Prerequisites

Before you begin, ensure you have the following installed on your machine:

- **PHP 8.1+** (for Laravel 12)
- **Composer** (for PHP dependency management)
- **Node.js** (version 16 or higher)
- **npm** or **Yarn** (for managing JavaScript dependencies)
- **MySQL** or **PostgreSQL** (or any database you prefer for Laravel)
- **Vite** (for frontend asset bundling)

---

## Installation

### Backend Setup (Laravel 12)

1. Clone the repository:

   ```bash
   git clone https://github.com/OragonDel08/music-app.git
   cd music-app-backend

2.  Install the backend dependencies using Composer:

    ```bash
    composer install

3. Copy the .env.example file to .env:

    ```bash
    cp .env.example .env

4. Generate the application key:

    ```bash
    php artisan key:generate

5. Set up the database:

    - Create a new database using MySQL.
    - Update the .env file with your database credentials:

    ```doenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password

6.  Run the migrations and seeders:

    ```bash
    php artisan migrate
    php artisan db:seed
