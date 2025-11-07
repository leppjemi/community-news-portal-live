# Railway Deployment Guide

This guide will help you deploy the Community News Portal to Railway with continuous deployment from Git.

## Prerequisites

1. A Railway account (sign up at [railway.app](https://railway.app))
2. A GitHub/GitLab/Bitbucket repository with your code
3. Railway CLI (optional, for local testing)

## Step 1: Prepare Your Repository

Ensure your code is pushed to your Git repository. The deployment will use either:
- **Dockerfile** (recommended for full control)
- **Nixpacks** (automatic Laravel detection)

Both configurations are provided in this repository.

## Step 2: Create a Railway Project

1. Go to [railway.app](https://railway.app) and sign in
2. Click "New Project"
3. Select "Deploy from GitHub repo" (or your Git provider)
4. Select your repository
5. Railway will automatically detect the project

## Step 3: Configure Environment Variables

In your Railway project dashboard, go to **Variables** and add the following:

### Required Environment Variables

```bash
APP_NAME="Community News Portal"
APP_ENV=production
APP_KEY=                    # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=                   # Will be set automatically by Railway

# Database (Railway will provide MySQL service)
DB_CONNECTION=mysql
DB_HOST=                   # Provided by Railway MySQL service
DB_PORT=3306
DB_DATABASE=              # Provided by Railway MySQL service
DB_USERNAME=              # Provided by Railway MySQL service
DB_PASSWORD=              # Provided by Railway MySQL service

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Mail Configuration (if using email)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

### Generate APP_KEY

You can generate the `APP_KEY` locally:
```bash
cd src
php artisan key:generate --show
```

Or Railway will generate it automatically if you add a build command.

## Step 4: Add MySQL Database Service

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"MySQL"**
3. Railway will automatically create a MySQL database
4. The connection variables will be available as environment variables
5. Link the database service to your application service

## Step 5: Configure Build Settings

### Option A: Using Dockerfile (Recommended)

Railway will automatically detect the `Dockerfile` in the root directory.

**Build Command:** (leave empty, Dockerfile handles it)

**Start Command:** (leave empty, Dockerfile handles it)

### Option B: Using Nixpacks

If you prefer Nixpacks (automatic Laravel detection):

1. In Railway project settings, set **Build Command** to:
   ```bash
   cd src && composer install --no-dev --optimize-autoloader && npm ci && npm run build
   ```

2. Set **Start Command** to:
   ```bash
   cd src && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

## Step 6: Configure Deployment Settings

1. Go to your service settings
2. Under **Deploy**, ensure:
   - **Root Directory:** Leave empty (or set to `src` if using Nixpacks)
   - **Watch Paths:** Leave empty (watches entire repo)
   - **Healthcheck Path:** `/` or `/health` (if you add a health check route)

## Step 7: Add Build and Deploy Hooks

### Build Hook (Optional)

Add a build hook to run migrations and optimize:

```bash
cd src && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### Deploy Hook (Optional)

Add a deploy hook to run migrations:

```bash
cd src && php artisan migrate --force
```

**Note:** Railway will automatically run migrations if you add them to the start command, but using deploy hooks is cleaner.

## Step 8: Set Up Custom Domain (Optional)

1. In Railway project settings, go to **Settings** → **Domains**
2. Click **"Generate Domain"** for a free Railway domain
3. Or add your custom domain
4. Update `APP_URL` environment variable to match your domain

## Step 9: Enable Continuous Deployment

Railway automatically deploys on every push to your main branch. To configure:

1. Go to **Settings** → **Service**
2. Under **Source**, ensure your Git repository is connected
3. Set **Branch** to `main` (or your production branch)
4. Enable **"Auto Deploy"**

## Step 10: Monitor Deployments

1. Go to **Deployments** tab to see deployment history
2. Check logs in the **Logs** tab
3. Monitor metrics in the **Metrics** tab

## Troubleshooting

### Build Fails

- Check build logs in Railway dashboard
- Ensure all environment variables are set
- Verify `composer.json` and `package.json` are in the `src/` directory

### Application Won't Start

- Check application logs
- Verify database connection variables
- Ensure `APP_KEY` is set
- Check that migrations have run

### Database Connection Issues

- Verify MySQL service is running
- Check database environment variables are linked
- Ensure database service is in the same project

### Assets Not Loading

- Verify `npm run build` completed successfully
- Check that `public/build` directory exists
- Ensure `APP_URL` is set correctly

## Environment-Specific Configuration

### Production Checklist

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] `APP_KEY` is set and secure
- [ ] Database credentials are secure
- [ ] `APP_URL` matches your domain
- [ ] Mail configuration is set (if using email)
- [ ] Storage is properly configured (consider Railway volumes for persistent storage)

## Railway CLI (Optional)

Install Railway CLI for local testing:

```bash
npm i -g @railway/cli
railway login
railway link
railway up
```

## Additional Resources

- [Railway Documentation](https://docs.railway.app)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Railway Discord](https://discord.gg/railway)

## Support

If you encounter issues:
1. Check Railway logs
2. Review Laravel logs in `storage/logs/laravel.log`
3. Verify all environment variables are set correctly
4. Check Railway status page

