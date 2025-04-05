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
    ```

    - **Admin Credentials**:
        - Email: `admin@example.com`
        - Password: `password`

    - **User Credentials**:
        - Email: `user@example.com`
        - Password: `password`


### Frontend Setup (Vue 3)

1. Navigate to the frontend directory:

    ```bash
    cd music-app-frontend

2. Install the frontend dependencies using npm:

    ```bash
    npm install


## Running the Project

### Running the Backend

1. Run the Laravel development server:

    ```bash
    php artisan serve
    ```
    By default, it will be accessible at http://localhost:8000.

### Running the Frontend

1. In the frontend directory, run the Vite development server:
    ```bash
    npm run dev
    ```
    This will start the Vite development server, typically accessible at http://localhost:3000.


## Environment Variables

The project uses .env files for both the backend (Laravel) and frontend (Vite). Ensure the following environment variables are set:

### Backend (.env)

- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- APP_KEY (generated automatically by php artisan key:generate)

### Frontend (.env)

- VITE_API_BASE_URL: The base URL of your backend API.