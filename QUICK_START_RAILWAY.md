# Quick Start: Deploy to Railway

## 🚀 Fast Deployment Steps

### 1. Push to GitHub
```bash
git add .
git commit -m "Add Railway deployment configuration"
git push origin main
```

### 2. Create Railway Project
1. Go to [railway.app](https://railway.app) and sign in
2. Click **"New Project"** → **"Deploy from GitHub repo"**
3. Select your repository
4. Railway will auto-detect the Dockerfile

### 3. Add MySQL Database
1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"MySQL"**
3. Railway creates the database automatically

### 4. Configure Environment Variables
In Railway project → **Variables**, add:

```bash
APP_NAME="Community News Portal"
APP_ENV=production
APP_DEBUG=false
APP_KEY=                    # Generate: php artisan key:generate --show
APP_URL=                    # Will be auto-set by Railway

# Database (auto-provided by Railway MySQL service)
DB_CONNECTION=mysql
# DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD are auto-set by Railway

SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

**To get APP_KEY:**
```bash
cd src
php artisan key:generate --show
```

### 5. Link Database to App
1. Go to your **MySQL service** in Railway
2. Click **"Variables"** tab
3. Copy the database connection variables
4. Go to your **App service** → **Variables**
5. Add the database variables (or use Railway's service linking)

### 6. Deploy!
Railway automatically deploys on every push to `main` branch.

## ✅ Verify Deployment

1. Check **Deployments** tab for build status
2. View **Logs** to see application output
3. Visit your Railway-generated domain

## 🔧 Common Issues

**Build fails:**
- Check that all environment variables are set
- Verify `APP_KEY` is generated

**Database connection error:**
- Ensure MySQL service is running
- Verify database variables are linked to app service

**Assets not loading:**
- Check build logs for npm errors
- Verify `npm run build` completed successfully

## 📚 Full Documentation

See [RAILWAY_DEPLOYMENT.md](./RAILWAY_DEPLOYMENT.md) for detailed instructions.

