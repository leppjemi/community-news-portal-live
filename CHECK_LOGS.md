# Check Railway Logs - 502 Error Troubleshooting

## Deployment Successful But 502 Error

Your deployment was successful, but you're still getting a 502 error. This means the container is running but Nginx can't connect to PHP-FPM.

## What to Check

### 1. Check Application Logs

Go to **Railway Dashboard** → **Your Laravel App Service** → **Logs** tab

Look for these messages:

**Expected to see:**
```
=== Starting Laravel Application ===
Configuring Nginx for port 80...
Nginx config created
Running Laravel initialization...
Starting Laravel initialization...
Running database migrations...
Clearing caches...
Caching Laravel components...
Laravel initialization complete!
Verifying PHP-FPM configuration...
Starting services (Nginx + PHP-FPM)...
INFO supervisord started with pid 1
INFO spawned: 'nginx' with pid 2
INFO spawned: 'php-fpm' with pid 3
INFO success: nginx entered RUNNING state
INFO success: php-fpm entered RUNNING state
```

**If you see errors:**
- Database connection errors → Check DB_* variables
- PHP-FPM not starting → Check PHP-FPM logs
- Nginx errors → Check Nginx configuration

### 2. Check PHP-FPM Status

In the logs, look for:
- `[NOTICE: fpm is running, pid X]`
- `[NOTICE: ready to handle connections]`

If you don't see these, PHP-FPM isn't starting.

### 3. Check Port 9000

The startup script now checks if port 9000 is listening. Look for:
- `Verifying Nginx can connect to PHP-FPM...`
- Port 9000 should be in the output

### 4. Common Issues

#### Issue: PHP-FPM Not Listening on Port 9000

**Symptoms:**
- Services start but 502 error
- No port 9000 in netstat output

**Solution:**
- Check PHP-FPM config file exists: `/usr/local/etc/php-fpm.d/zz-railway.conf`
- Verify it contains `listen = 127.0.0.1:9000`

#### Issue: Database Connection Failing

**Symptoms:**
- Initialization script fails
- Migration errors in logs

**Solution:**
- Verify MySQL service is linked
- Check DB_* variables are set correctly
- Ensure variables use `${{}}` syntax

#### Issue: Nginx Can't Find PHP Files

**Symptoms:**
- 502 error
- Nginx running but PHP-FPM not responding

**Solution:**
- Check `root /var/www/html/public;` in Nginx config
- Verify `index.php` exists in public directory
- Check file permissions

## Quick Diagnostic Commands

If you have Railway CLI access:

```bash
railway run sh
# Then inside container:
netstat -tlnp | grep 9000
ps aux | grep php-fpm
ps aux | grep nginx
cat /etc/nginx/http.d/default.conf
cat /usr/local/etc/php-fpm.d/zz-railway.conf
```

## Next Steps

1. **Check the logs** - Share the last 50 lines from Railway logs
2. **Verify services are running** - Both nginx and php-fpm should show as RUNNING
3. **Check PHP-FPM config** - Verify the custom config file exists and is correct

## Share Logs

Please share:
1. The last 50-100 lines from Railway logs
2. Any error messages you see
3. Whether you see "Starting services..." message
4. Whether both nginx and php-fpm show as RUNNING

This will help identify the exact issue!

