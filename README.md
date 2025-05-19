# Role-Based Security Awareness Training

A prototype web application demonstrating a role-specific security awareness training workflow. Users log in, answer questions tailored to their job role, and must retake topics they fail after watching a brief refresher video.

## Features

- **Role‑topic Mapping**: Each user role (e.g. Product Manager, Software Engineer) has a custom set of training topics.
- **Adaptive Quiz Flow**:
    - Initial quiz covers all topics assigned to the user.
    - Failed topics require watching a tutorial video before a focused retake.
- **Progress Tracking**:
    - Records training session and pass/fail status.
    - Marks a session complete only when all topics are passed.
- **Server‑Driven UI**: Uses Inertia.js for single‑page navigation.
- **Secure Input**: Dynamic validation ensures each question is answered and options belong to the correct question.

## Technology Stack

- **Backend**: PHP 8.x, [Laravel](https://laravel.com/) (routing, migrations, ORM)
- **Frontend**: [React](https://reactjs.org/) + [Inertia.js](https://inertiajs.com/) (server-driven views)
- **Styling**: Tailwind CSS and a shared component library for cards, buttons, badges, etc.
- **Database**: MySQL / PostgreSQL (configured via Laravel)

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- A local database (MySQL, MariaDB, or PostgreSQL)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/artioladoli/security_awareness_training.git
   cd security_awareness_training
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
    - Copy `.env.example` to `.env` and update database credentials
    - Generate an application key:
      ```bash
      php artisan key:generate
      ```

5. **Run migrations & seed predefined data**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compile assets**
   ```bash
   npm run dev
   ```

## Running the Application

Start the development server:
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser. Log in with the seeded user credentials:

```
Email: software@example.com
Password: Software
```

## Usage

- After login, you are redirected to the training quiz.
- Answer all questions. Upon submission, you’ll see per‑topic results.
- For any failed topic click **Retake test**.
- Once all topics are passed, your session is marked **Finished**.
