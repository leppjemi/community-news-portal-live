<div align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>

# Community News Submission Portal

[![Build Status](https://img.shields.io/badge/build-passing-brightgreen?style=flat-square)](https://github.com/leppjemi/community-news-portal-live/actions)
[![Laravel Version](https://img.shields.io/badge/laravel-v12.0-red?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/php-v8.3-blue?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](https://opensource.org/licenses/MIT)

A community-driven news portal where people can share stories, editors review submissions, and everyone can discover interesting articles. Built with **Laravel 12**, **Livewire 3**, and **Tailwind CSS**.

[Report Bug](https://github.com/leppjemi/community-news-portal-live/issues) · [Request Feature](https://github.com/leppjemi/community-news-portal-live/issues)

</div>

---

## 📑 Table of Contents

- [🚀 What's Inside](#-whats-inside)
- [📋 What You'll Need](#-what-youll-need)
- [📥 Getting Started](#-getting-started)
- [🔐 Test Accounts](#-test-accounts)
- [🔄 Project Workflows](#-project-workflows)
- [⚠️ Don't Have `make` Installed?](#-dont-have-make-installed)
- [🎯 Daily Usage](#-daily-usage)
- [🛠️ Handy Commands](#-handy-commands)
- [🧪 Testing](#-testing)
- [🔧 Development Tips](#-development-tips)
- [📁 Project Structure](#-project-structure)
- [🐛 Troubleshooting](#-troubleshooting)
- [🤝 Contributing](#-contributing)

---

## 🚀 What's Inside

-   **Role-Based Access**: Different user roles (guests, users, editors, admins) - each with their own permissions.
-   **Submissions**: Users can submit news articles with images.
-   **Editorial Workflow**: Submissions go through a review process before publishing.
-   **Public Feed**: News feed with search and category filtering.
-   **Engagement**: Likes, view counts, and social sharing.
-   **Admin Panel**: Manage categories and users.
-   **Responsive**: Works great on mobile and desktop.

---

## 📋 What You'll Need

You'll need a few things installed before we get started:

-   **Docker Desktop** (version 20.10 or newer)
    -   [Windows](https://www.docker.com/products/docker-desktop/)
    -   [macOS](https://www.docker.com/products/docker-desktop/)
    -   [Linux](https://docs.docker.com/engine/install/)
-   **Git**
-   **Make** (for running setup commands)
    -   **Windows**: via WSL, Git Bash, or [Chocolatey](https://chocolatey.org/) (`choco install make`)
    -   **macOS**: `brew install make`
    -   **Linux**: `sudo apt install make`

> [!NOTE]
> Docker Compose comes bundled with Docker Desktop, so you don't need to install it separately.

To check if you're ready:

```bash
docker --version
docker compose version
```

---

## 📥 Getting Started

First time setup is straightforward.

### Step 1: Clone the repo

```bash
git clone https://github.com/leppjemi/community-news-portal-live
cd community-news-portal-live
```

### Step 2: Run the setup

Make sure Docker Desktop is running, then run:

```bash
make setup-all
```

> [!TIP]
> This command builds containers, installs dependencies, sets up the database, and seeds test data. It may take a few minutes, so grab a coffee ☕

#### What `make setup-all` Does

1.  **Builds & Starts**: Creates Docker images and starts services (app, nginx, db, phpMyAdmin).
2.  **Waits for DB**: Ensures MySQL is ready.
3.  **Configures**: Sets up `.env` and fixes permissions.
4.  **Installs**: Runs `composer install` and `npm install`.
5.  **Builds Assets**: Compiles frontend with Vite.
6.  **Migrates & Seeds**: Sets up the database with test data.

> [!IMPORTANT]
> **WSL Users**: If you encounter container name conflicts, run `docker compose down` first, then `make up` again. The project uses an explicit name (`community-news-portal`) to prevent issues between WSL and Windows.

Once done, access the app:

-   **Main App**: [http://localhost:8000](http://localhost:8000) or [http://127.0.0.1:8000](http://127.0.0.1:8000)
-   **phpMyAdmin**: [http://localhost:8080](http://localhost:8080) or [http://127.0.0.1:8080](http://127.0.0.1:8080)

---

## 🔐 Test Accounts

The setup creates these default accounts:

| Role | Email | Password | Scope |
|------|-------|----------|-------|
| **Admin** | `admin@example.com` | `password` | System Management |
| **Editors** | `editor1@example.com` ... `editor3@example.com` | `password` | Content Moderation |
| **Users** | `user1@example.com` ... `user5@example.com` | `password` | Submission |

> [!WARNING]
> Change these credentials if deploying to a public environment!

---

## 🔄 Project Workflows

### 1. 👤 Guest (Visitor)
*No account required.*
-   **Browse**: View the latest news on the homepage.
-   **Search & Filter**: Find articles by keyword or category.
-   **Read**: Access full article content.
-   **Share**: Share articles to social media.

### 2. ✍️ Registered User
*Default role upon registration.*
-   **Register**: Sign up for an account. **You are assigned the 'User' role by default.**
-   **Submit News**:
    1.  Go to Dashboard → "Submit News".
    2.  Fill in the title, category, content, and upload an image.
    3.  Click "Submit". The article status becomes **Pending**.
-   **Track Status**: View your submissions in the Dashboard.
    -   **Pending**: Waiting for editor review.
    -   **Published**: Live on the site.
    -   **Rejected**: Returned with feedback (if applicable).
-   **Profile**: Update your name, email, and password.

### 3. 🧐 Editor
*Assigned by Admin only.*
-   **Review Queue**: Access the "Review Queue" to see all **Pending** submissions.
-   **Approve/Reject**:
    -   **Approve**: The article is immediately **Published** to the homepage.
    -   **Reject**: The article is marked as **Rejected** and hidden from the public.
-   **Manage Content**: Edit or delete existing articles if necessary.
-   **Dashboard**: View stats on pending and published articles.

### 4. 🛡️ Administrator
*Full system access.*
-   **User Management**:
    -   View all registered users.
    -   **Promote/Demote**: Edit a user to change their role (e.g., promote a 'User' to 'Editor').
    -   *Note: Only Admins can assign the 'Editor' or 'Admin' role.*
-   **Category Management**: Create, edit, or delete news categories.
-   **Analytics**: Monitor platform engagement (views, likes, shares).
-   **System Maintenance**: Manage site-wide settings.

---

## ⚠️ Don't Have `make` Installed?

If you prefer not to install `make`, you can run commands directly.

<details>
<summary><strong>Option 1: Install Make (Recommended)</strong></summary>

-   **Windows (WSL)**: `sudo apt update && sudo apt install -y make`
-   **Windows (Chocolatey)**: `choco install make`
-   **macOS**: `brew install make`
-   **Linux**: `sudo apt install make`

</details>

<details>
<summary><strong>Option 2: Run Docker Commands Directly</strong></summary>

Replace `make setup-all` with:

```bash
# Set project name
PROJECT_NAME=community-news-portal

# 1. Build & Start
docker compose -p $PROJECT_NAME -f docker-compose.yml build
docker compose -p $PROJECT_NAME -f docker-compose.yml up -d

# 2. Wait for DB
sleep 10

# 3. Setup Env
if [ ! -f src/.env ]; then cp src/.env.example src/.env; fi

# 4. Fix Permissions
docker compose -p $PROJECT_NAME exec app sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public storage/logs bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

# 5. Install Dependencies
docker compose -p $PROJECT_NAME exec app composer install
docker compose -p $PROJECT_NAME exec app npm install

# 6. Generate Key
docker compose -p $PROJECT_NAME exec app php artisan key:generate

# 7. Build Assets
docker compose -p $PROJECT_NAME exec app npm run build

# 8. Migrate & Seed
docker compose -p $PROJECT_NAME exec app php artisan migrate
docker compose -p $PROJECT_NAME exec app php artisan db:seed
```

</details>

---

## 🔄 Manual Setup (Step-by-Step)

If `make setup-all` fails or you want granular control:

<details>
<summary><strong>Click to expand manual steps</strong></summary>

1.  **Clone**: `git clone https://github.com/leppjemi/community-news-portal-live`
2.  **Start Docker**: Ensure Docker Desktop is running.
3.  **Up**: `make up`
4.  **Composer**: `make composer-install`
5.  **NPM**: `make npm-install`
6.  **Build**: `make npm-build`
7.  **Env**: Check `src/.env` exists.
8.  **Permissions**: `make fix-permission`
9.  **Migrate**: `make migrate`
10. **Seed**: `make seed`

</details>

---

## 🎯 Daily Usage

Start the environment:

```bash
make up
```

Stop the environment:

```bash
make down
```

Restart (rebuild):

```bash
make restart
```

---

## 🛠️ Handy Commands

| Command | Description |
|---------|-------------|
| `make setup-all` | **Full Setup**: Builds, installs, migrates, and seeds. |
| `make up` | Start containers. |
| `make down` | Stop containers. |
| `make restart` | Rebuild and restart. |
| `make composer-install` | Install PHP dependencies. |
| `make npm-install` | Install Node dependencies. |
| `make npm-dev` | Start Vite dev server (hot reload). |
| `make npm-build` | Build frontend assets for production. |
| `make migrate` | Run DB migrations. |
| `make migrate-fresh` | Reset DB and re-seed. |
| `make seed` | Run DB seeders. |
| `make fix-permission` | Fix storage permissions. |
| `make test` | Run PHPUnit/Pest tests. |

### Running Artisan Commands

```bash
# Clear cache
make artisan cmd="cache:clear"

# Create controller
make artisan cmd="make:controller ExampleController"
```

---

## 🧪 Testing

Run the test suite:

```bash
make test
```

Or manually:

```bash
docker compose exec app php artisan test
```

---

## 🔧 Development Tips

### Frontend Work
For hot reloading (instant CSS/JS updates):

```bash
make npm-dev
```

### Checking Logs

```bash
# All logs
docker compose logs -f

# Specific service
docker compose logs -f app
docker compose logs -f db
```

### Accessing Containers

```bash
# PHP Shell
docker compose exec app bash

# MySQL Shell
docker compose exec db mysql -u user -puser community_db
```

---

## 📁 Project Structure

```
community-news-portal/
├── docker/                 # Docker configuration (Nginx, PHP)
├── src/                    # Laravel Application
│   ├── app/                # Controllers, Models, Livewire
│   ├── database/           # Migrations, Seeders
│   ├── resources/          # Views (Blade), CSS, JS
│   └── routes/             # Web routes
├── docker-compose.yml      # Service definitions
├── Makefile                # Shortcut commands
└── README.md               # Documentation
```

---

## � Troubleshooting

| Issue | Solution |
|-------|----------|
| **Port Conflict** | Edit `docker-compose.yml` (e.g., change `8000:80` to `8001:80`) then `make up`. |
| **Permission Denied** | Run `make fix-permission`. |
| **Vite Manifest Missing** | Run `make npm-build`. |
| **DB Connection Failed** | Check `docker compose logs db`. Wait 10s for MySQL to initialize. |
| **Container Conflicts** | Run `docker compose down` then `make up`. |

---

## 🛠️ Tech Stack

-   **Backend**: [Laravel 12](https://laravel.com)
-   **Frontend**: [Livewire 3](https://livewire.laravel.com)
-   **Styling**: [Tailwind CSS v4.1](https://tailwindcss.com) & [DaisyUI v5](https://daisyui.com)
-   **Database**: MySQL 8
-   **Server**: Nginx
-   **Containerization**: Docker & Docker Compose
-   **Bundler**: Vite 7
-   **PHP**: 8.3

---

## 🌐 Accessing the Application

-   **Browser**: [http://localhost:8000](http://localhost:8000)
-   **Direct IP**: [http://127.0.0.1:8000](http://127.0.0.1:8000) (Better for CI/CD)

> [!NOTE]
> If `localhost` doesn't work, try `127.0.0.1`.

---

## 🤝 Contributing

1.  Fork the repo
2.  Create a branch (`git checkout -b feature/amazing-feature`)
3.  Commit changes
4.  Run tests: `make test`
5.  Push to branch
6.  Open a Pull Request

---

<div align="center">

**[MIT License](https://opensource.org/licenses/MIT)**

</div>
