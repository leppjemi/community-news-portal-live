# Variables
PROJECT_NAME = community-news-portal
DOCKER_COMPOSE = docker compose -p $(PROJECT_NAME) -f docker-compose.yml
PHP_CONTAINER = app
NGINX_CONTAINER = nginx
DB_CONTAINER = db

# Preserve working directory for recursive make calls (fixes WSL getcwd issue)
MAKEDIR := $(CURDIR)

up:
	$(DOCKER_COMPOSE) up -d 

build:
	$(DOCKER_COMPOSE) build

down:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) down && $(DOCKER_COMPOSE) up -d --build

composer-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer install

npm-install:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm install

npm-dev:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm run dev

npm-build:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) npm run build

migrate:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate

migrate-fresh:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate:fresh --seed

artisan:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan $(cmd)

fix-permission:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) sh -c "mkdir -p storage/framework/{sessions,views,cache} storage/app/public storage/logs bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

seed:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan db:seed

test:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan test

# Wait for database to be ready
wait-db:
	@echo "Waiting for database to be ready..."
	@for i in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15; do \
		if $(DOCKER_COMPOSE) exec -T $(PHP_CONTAINER) php -r "try { new PDO('mysql:host=db;dbname=community_db', 'user', 'user'); echo 'Database is ready!'; exit(0); } catch (Exception $$e) { exit(1); }" 2>/dev/null; then \
			break; \
		fi; \
		echo "Waiting for database... (attempt $$i/15)"; \
		sleep 3; \
	done

# Setup .env file if it doesn't exist
setup-env:
	@if [ ! -f src/.env ]; then \
		if [ -f src/.env.example ]; then \
			echo "Copying .env.example to .env..."; \
			cp src/.env.example src/.env; \
		else \
			echo "Warning: .env.example not found. Creating basic .env file..."; \
			touch src/.env; \
		fi; \
	else \
		echo ".env file already exists, skipping..."; \
	fi

# Generate application key
key-generate:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan key:generate

# Complete setup for first-time users
setup-all:
	@echo "🚀 Starting complete setup..."
	@echo ""
	@echo "Step 1/9: Building and starting containers..."
	$(DOCKER_COMPOSE) build
	$(DOCKER_COMPOSE) up -d
	@echo ""
	@echo "Step 2/9: Waiting for database to be ready..."
	@sleep 5
	@cd $(MAKEDIR) && $(MAKE) wait-db
	@echo ""
	@echo "Step 3/9: Setting up .env file..."
	@cd $(MAKEDIR) && $(MAKE) setup-env
	@echo ""
	@echo "Step 4/9: Fixing permissions..."
	@cd $(MAKEDIR) && $(MAKE) fix-permission
	@echo ""
	@echo "Step 5/9: Installing Composer dependencies..."
	@cd $(MAKEDIR) && $(MAKE) composer-install
	@echo ""
	@echo "Step 6/9: Generating application key..."
	@cd $(MAKEDIR) && $(MAKE) key-generate || echo "Key already exists, skipping..."
	@echo ""
	@echo "Step 7/9: Installing NPM dependencies..."
	@cd $(MAKEDIR) && $(MAKE) npm-install
	@echo ""
	@echo "Step 8/9: Building assets..."
	@cd $(MAKEDIR) && $(MAKE) npm-build
	@echo ""
	@echo "Step 9/9: Running migrations and seeding database..."
	@cd $(MAKEDIR) && $(MAKE) migrate
	@cd $(MAKEDIR) && $(MAKE) seed
	@echo ""
	@echo "✅ Setup complete! Your application is ready."
	@echo ""
	@echo "🌐 Access your application at: http://localhost:8000"
	@echo "📊 Access phpMyAdmin at: http://localhost:8080"
	@echo ""
	@echo "Default login credentials:"
	@echo "  Admin:   admin@example.com / password"
	@echo "  Editor:  editor@example.com / password"
	@echo "  User:    user@example.com / password"