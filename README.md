# Community News Submission Portal

A community-driven news portal where people can share stories, editors review submissions, and everyone can discover interesting articles. Built with Laravel 12, Livewire 3, and Tailwind CSS.

## 🚀 What's Inside

- Different user roles (guests, users, editors, admins) - each with their own permissions
- Users can submit news articles with images
- Editorial review system - submissions go through a review process before publishing
- Public news feed with search and category filtering
- Engagement features - likes, view counts, and social sharing
- Admin panel for managing categories and users
- Responsive design that works great on mobile and desktop

## 📋 What You'll Need

You'll need a few things installed before we get started:

- **Docker Desktop** (version 20.10 or newer should work fine)
  - [Windows](https://www.docker.com/products/docker-desktop/)
  - [macOS](https://www.docker.com/products/docker-desktop/)
  - [Linux](https://docs.docker.com/engine/install/)
- **Git** (you probably already have this)
- **Make** (for running the setup commands)
  - **Windows**: Install via WSL, Git Bash (comes with Git), or [Chocolatey](https://chocolatey.org/) (`choco install make`)
  - **macOS**: Usually pre-installed, or install via Homebrew (`brew install make`)
  - **Linux**: Install via package manager (`sudo apt install make` for Ubuntu/Debian)

Docker Compose comes bundled with Docker Desktop, so you don't need to install it separately.

To make sure Docker is working, just run:

```bash
docker --version
docker compose version
```

If you see version numbers, you're good to go! If not, check out Docker's docs or make sure Docker Desktop is actually running.

## 📥 Getting Started

Alright, let's get this thing running! First time setup is pretty straightforward.

### Step 1: Clone the repo

```bash
git clone https://github.com/leppjemi/community-news-portal
cd community-news-portal
```
<｜tool▁calls▁begin｜><｜tool▁call▁begin｜>
read_file

### Step 2: Run the setup

Make sure Docker Desktop is running, then just run:

```bash
make setup-all
```

This one command does everything for you - builds the containers, installs dependencies, sets up the database, creates some test users, the whole shebang. It'll take a few minutes the first time (especially if you're downloading Docker images), so grab a coffee ☕

#### What `make setup-all` Does

The `setup-all` command performs the following steps automatically:

1. **Builds and starts containers** - Creates Docker images and starts all services (app, nginx, database, phpMyAdmin)
2. **Waits for database** - Ensures MySQL is ready before proceeding
3. **Sets up .env file** - Creates environment configuration from `.env.example` if it doesn't exist
4. **Fixes permissions** - Sets proper file permissions for Laravel storage and cache directories
5. **Installs Composer dependencies** - Downloads all PHP packages required by Laravel
6. **Generates application key** - Creates a unique encryption key for Laravel
7. **Installs NPM dependencies** - Downloads all Node.js packages for frontend assets
8. **Builds frontend assets** - Compiles CSS and JavaScript using Vite
9. **Runs migrations and seeds** - Creates database tables and populates with test data

> **Note for WSL Users**: If you run `make up` from WSL terminal and then try to use Docker from Windows (or vice versa), you might see container name conflicts. This is because Docker Compose uses different project names based on the directory path. The project is configured with an explicit project name (`community-news-portal`) to prevent this issue. If you encounter conflicts, run `docker compose down` first, then `make up` again.

Once it's done, you're ready to go! Open your browser and head to:

- **Main app**: http://localhost:8000 or http://127.0.0.1:8000
- **phpMyAdmin** (if you need it): http://localhost:8080 or http://127.0.0.1:8080

> **Note**: Both `localhost` and `127.0.0.1` should work in your browser. If you're using automated tools or CI/CD, `127.0.0.1` is more reliable as it doesn't require hostname resolution.

### Test Accounts

The setup creates a few test accounts you can use:

- **Admin**: `admin@example.com` / `password`
- **Editor**: `editor@example.com` / `password`  
- **Regular user**: `user@example.com` / `password`

Obviously, change these if you're putting this anywhere public!

---

## 📖 How It Works

Think of this like a community newspaper where anyone can contribute, but there's an editorial process to keep things quality.

### For Visitors (No Account Needed)

You can browse all the published articles on the homepage, search for stuff you're interested in, filter by category, and read the full stories. You'll also see how many people liked each article and how many views it got.

### For Registered Users

Once you create an account, you can:
- Submit your own news articles (with images!)
- See all your submissions and whether they're still pending or got published
- Like articles you find interesting
- Share articles on social media

### For Editors

Editors get a review queue where they can see all the articles waiting for approval. They can read through submissions and decide whether to publish them or not. They can also manage published content.

### For Admins

Admins can do pretty much everything - manage users, create/edit categories, oversee all content. The usual admin stuff.

### The Flow

Here's how an article makes it to the homepage:

1. Someone submits a news article → it goes into "Pending" status
2. An editor takes a look and decides if it's good to go
3. If approved, it gets published and shows up on the homepage
4. People can then like it, share it, and engage with it

Pretty simple, right?

---

## ⚠️ Don't Have `make` Installed?

If you don't have `make` installed, you have a few options:

### Option 1: Install `make` (Recommended)

**Windows:**
- **WSL**: If you have WSL, run `sudo apt update && sudo apt install -y make`
- **Git Bash**: If you have Git for Windows, `make` comes bundled with Git Bash
- **Chocolatey**: Run `choco install make` in PowerShell (as Administrator)
- **Manual**: Download from [GnuWin32](http://gnuwin32.sourceforge.net/packages/make.htm)

**macOS:**
- Usually pre-installed. If not: `brew install make`

**Linux:**
- Ubuntu/Debian: `sudo apt install make`
- Fedora/RHEL: `sudo dnf install make`
- Arch: `sudo pacman -S make`

### Option 2: Run Commands Directly (No `make` Required)

If you prefer not to install `make`, you can run the Docker Compose commands directly. Replace `make setup-all` with these commands:

```bash
# Set project name variable
PROJECT_NAME=community-news-portal

# Step 1: Build and start containers
docker compose -p $PROJECT_NAME -f docker-compose.yml build
docker compose -p $PROJECT_NAME -f docker-compose.yml up -d

# Step 2: Wait for database (wait a few seconds)
sleep 10

# Step 3: Setup .env file (if it doesn't exist)
if [ ! -f src/.env ]; then
  cp src/.env.example src/.env 2>/dev/null || touch src/.env
fi

# Step 4: Fix permissions
docker compose -p $PROJECT_NAME exec app sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public storage/logs bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

# Step 5: Install Composer dependencies
docker compose -p $PROJECT_NAME exec app composer install

# Step 6: Generate application key
docker compose -p $PROJECT_NAME exec app php artisan key:generate

# Step 7: Install NPM dependencies
docker compose -p $PROJECT_NAME exec app npm install

# Step 8: Build frontend assets
docker compose -p $PROJECT_NAME exec app npm run build

# Step 9: Run migrations and seed database
docker compose -p $PROJECT_NAME exec app php artisan migrate
docker compose -p $PROJECT_NAME exec app php artisan db:seed
```

**For Windows PowerShell/CMD**, use these commands instead:

```powershell
# Set project name
$PROJECT_NAME = "community-news-portal"

# Step 1: Build and start containers
docker compose -p $PROJECT_NAME -f docker-compose.yml build
docker compose -p $PROJECT_NAME -f docker-compose.yml up -d

# Step 2: Wait for database
Start-Sleep -Seconds 10

# Step 3: Setup .env file
if (-not (Test-Path "src\.env")) {
    if (Test-Path "src\.env.example") {
        Copy-Item "src\.env.example" "src\.env"
    } else {
        New-Item "src\.env" -ItemType File
    }
}

# Step 4: Fix permissions
docker compose -p $PROJECT_NAME exec app sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public storage/logs bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

# Step 5: Install Composer dependencies
docker compose -p $PROJECT_NAME exec app composer install

# Step 6: Generate application key
docker compose -p $PROJECT_NAME exec app php artisan key:generate

# Step 7: Install NPM dependencies
docker compose -p $PROJECT_NAME exec app npm install

# Step 8: Build frontend assets
docker compose -p $PROJECT_NAME exec app npm run build

# Step 9: Run migrations and seed database
docker compose -p $PROJECT_NAME exec app php artisan migrate
docker compose -p $PROJECT_NAME exec app php artisan db:seed
```

---

## 🔄 Manual Setup (If You Need It)

The `make setup-all` command should handle everything, but if you want to do things step by step (or if something went wrong), here's how:

<details>
<summary>Click to see the manual steps</summary>

### Step 1: Clone the Repository

```bash
git clone https://github.com/leppjemi/community-news-portal
cd community-news-portal
```

### Step 2: Start Docker Desktop

Make sure Docker Desktop is running on your machine.

### Step 3: Build and Start Containers

```bash
make up
```

### Step 4: Install PHP Dependencies

```bash
make composer-install
```

### Step 5: Install Node.js Dependencies

```bash
make npm-install
```

### Step 6: Build Frontend Assets

```bash
make npm-build
```

### Step 7: Set Up Environment File

The `.env` file will be automatically created. If needed, edit `src/.env`:

```bash
# Database configuration for Docker:
# DB_HOST=db
# DB_DATABASE=community_db
# DB_USERNAME=user
# DB_PASSWORD=user
```

### Step 8: Fix Storage Permissions

```bash
make fix-permission
```

### Step 9: Run Database Migrations

```bash
make migrate
```

### Step 10: Seed the Database

```bash
make seed
```

This creates default users and sample data.

</details>

## 🎯 Daily Usage

After the initial setup, using the app is super simple. Just make sure Docker Desktop is running, then:

```bash
make up
```

That's it! Your app will be available at:
- http://localhost:8000 or http://127.0.0.1:8000

> **Why both addresses?** 
> - `localhost` is a hostname that gets resolved via DNS or `/etc/hosts`
> - `127.0.0.1` is a direct IP address (IPv4 loopback)
> - Both work in regular browsers, but `127.0.0.1` is more reliable for automated tools
> - If `localhost` doesn't work (rare), try `127.0.0.1` instead

### Stopping Everything

When you're done for the day:

```bash
make down
```

### Restarting

If something feels off and you want to restart everything:

```bash
make restart
```

This will rebuild and restart all containers. Sometimes that fixes weird issues.

## 🛠️ Handy Commands

I've set up a Makefile with some shortcuts to make life easier:

| Command | What It Does |
|---------|-------------|
| `make setup-all` | **The big one** - does everything for first-time setup |
| `make up` | Start all the containers |
| `make down` | Stop everything |
| `make restart` | Rebuild and restart containers |
| `make composer-install` | Install PHP packages |
| `make npm-install` | Install Node packages |
| `make npm-dev` | Start the dev server (for frontend work) |
| `make npm-build` | Build the frontend assets |
| `make migrate` | Run database migrations |
| `make migrate-fresh` | Drop all tables, re-run migrations, and seed database |
| `make seed` | Populate the database with test data |
| `make fix-permission` | Fix file permission issues |
| `make test` | Run the test suite |
| `make artisan cmd=<command>` | Run any Laravel artisan command |

### Running Artisan Commands

You can run any Laravel artisan command through the makefile:

```bash
# Clear cache
make artisan cmd="cache:clear"

# Create a new controller
make artisan cmd="make:controller ExampleController"
```

## 🧪 Testing

To run the tests:

```bash
make test
```

Or if you prefer the long way:

```bash
docker compose exec app php artisan test
```

## 🔧 Development Tips

### Frontend Work

If you're tweaking CSS or JavaScript and want to see changes instantly:

```bash
make npm-dev
```

This fires up the Vite dev server with hot reloading. Keep it running in a separate terminal while you work.

### Checking Logs

When things go wrong (and they will), logs are your friend:

```bash
# See everything
docker compose logs -f

# Or just one service
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db
```

### Jumping Into Containers

Sometimes you need to poke around inside:

```bash
# Get a shell in the PHP container
docker compose exec app bash

# Or connect to the database directly
docker compose exec db mysql -u user -puser community_db
```

## 📁 Project Structure

```
community-news-portal/
├── docker/
│   ├── nginx/
│   │   └── default.conf          # Nginx configuration
│   └── php/
│       ├── Dockerfile            # PHP-FPM container definition
│       └── docker-entrypoint.sh  # Container startup script
├── src/                          # Laravel application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/      # Application controllers
│   │   │   └── Middleware/       # Custom middleware
│   │   ├── Livewire/             # Livewire components
│   │   ├── Models/               # Eloquent models
│   │   └── Policies/             # Authorization policies
│   ├── database/
│   │   ├── migrations/           # Database migrations
│   │   ├── seeders/              # Database seeders
│   │   └── factories/            # Model factories
│   ├── resources/
│   │   ├── views/                # Blade templates
│   │   ├── css/                  # CSS files
│   │   └── js/                   # JavaScript files
│   ├── routes/
│   │   └── web.php               # Web routes
│   └── tests/                    # PHPUnit tests
├── docker-compose.yml             # Docker services configuration
├── Makefile                       # Convenient commands
└── README.md                      # This file
```

## 🔐 Test Accounts

After running `make seed`, you'll have these accounts ready to go:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Editor | editor@example.com | password |
| User | user@example.com | password |

**Heads up**: Don't use these in production! Change them before deploying anywhere real.

## 🐛 When Things Go Wrong

### Port Already in Use

Got an error about port 8000 or 3306 being taken? Either stop whatever's using it, or change the port in `docker-compose.yml`:

```yaml
ports:
  - "8001:80"  # Use 8001 instead of 8000
```

### Permission Errors

If you're getting permission denied errors with storage:

```bash
make fix-permission
```

Usually fixes it.

### "Vite manifest not found"

This means the frontend hasn't been built yet:

```bash
make npm-build
```

### Database Won't Connect

First, make sure the database container is actually running:

```bash
docker compose ps
```

If it's running but still not connecting, check the logs:

```bash
docker compose logs db
```

Also, MySQL takes a few seconds to start up. Give it a moment after starting containers.

### Containers Refuse to Start

Check what's going on:

```bash
docker compose logs app
```

If that doesn't help, try rebuilding:

```bash
make down
make up
```

### Container Name Conflicts

If you see errors like "container name already in use" when running `make up`:

This usually happens when containers were created from a different directory (e.g., WSL vs Windows). The project is configured with an explicit name to prevent this, but if it occurs:

```bash
# Stop and remove existing containers
docker compose down

# Remove any orphaned containers
docker ps -a | grep community
docker rm <container-id>

# Then start fresh
make up
```

The project uses `name: community-news-portal` in `docker-compose.yml` and `-p community-news-portal` in the Makefile to ensure consistent naming regardless of where you run it from.

### Clearing Caches

Sometimes Laravel's caches get weird. Clear them all:

```bash
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Livewire 3, Tailwind CSS v4.1, DaisyUI v5
- **Database**: MySQL 8
- **Web Server**: Nginx
- **Containerization**: Docker & Docker Compose
- **Asset Bundling**: Vite 7
- **PHP**: 8.3

## 📝 A Few Notes

- **Database persistence**: Database data persists in Docker volumes, so you won't lose it when you stop containers
- **Hot reloading**: Your source code is mounted as a volume, so changes show up immediately (no rebuild needed)
- **Auto permissions**: Storage permissions get fixed automatically when containers start
- **Database access**: phpMyAdmin is available at http://localhost:8080 or http://127.0.0.1:8080 if you need to poke around the database
- **Project naming**: The Docker Compose project uses an explicit name (`community-news-portal`) to ensure consistency when running from WSL or Windows
- **Network access**: The application listens on `0.0.0.0:8000`, making it accessible via both `localhost` and `127.0.0.1`

## 🌐 Accessing the Application

### Browser Access

You can access the application using either:

- **Hostname**: http://localhost:8000
- **IP Address**: http://127.0.0.1:8000

Both should work in your regular browser. The difference:

- **`localhost`**: A hostname that requires DNS/hostname resolution. May resolve to IPv4 (`127.0.0.1`) or IPv6 (`::1`)
- **`127.0.0.1`**: Direct IPv4 loopback address, no resolution needed. More reliable for automated tools and CI/CD

### Troubleshooting Access Issues

If you can't access the application:

1. **Check containers are running**:
   ```bash
   docker compose ps
   ```
   All services should show "Up" status.

2. **Check port mapping**:
   ```bash
   docker compose port nginx 80
   ```
   Should show `0.0.0.0:8000`

3. **Try the IP address directly**:
   If `localhost` doesn't work, try `127.0.0.1:8000` instead

4. **Check firewall settings**: Make sure your firewall isn't blocking port 8000

5. **Check Docker Desktop**: Ensure Docker Desktop is running and WSL integration is enabled (if on Windows)

## 🤝 Contributing

Want to help out? Awesome!

1. Fork the repo
2. Create a branch for your changes
3. Make your changes
4. Run the tests: `make test`
5. Send a pull request

That's it!
