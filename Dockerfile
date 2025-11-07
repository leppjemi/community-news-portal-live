# Production Dockerfile for Railway
FROM php:8.3-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    bash \
    curl \
    tzdata \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev \
    nodejs \
    npm \
    nginx \
    supervisor \
    net-tools \
    iproute2 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY src/ /var/www/html/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install Node dependencies and build assets
RUN npm ci && npm run build && rm -rf node_modules

# Set up storage and cache directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/app/public \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Configure PHP-FPM to listen on TCP port 9000
# Disable default www.conf and create our own
RUN mv /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf.default || true

# Create a custom PHP-FPM pool configuration that will be loaded
RUN echo '[www]' > /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'user = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'group = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'listen = 127.0.0.1:9000' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'listen.owner = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'listen.group = www-data' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'listen.mode = 0660' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'pm.max_children = 5' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'pm.start_servers = 2' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'pm.min_spare_servers = 1' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'pm.max_spare_servers = 3' >> /usr/local/etc/php-fpm.d/zz-railway.conf \
    && echo 'pm.max_requests = 500' >> /usr/local/etc/php-fpm.d/zz-railway.conf

# Remove any default Nginx configs to ensure ours is used
RUN rm -f /etc/nginx/http.d/*.conf /etc/nginx/conf.d/*.conf 2>/dev/null || true

# Create a default config (will be overridden by startup script, but ensures nginx can start)
RUN echo 'server {' > /etc/nginx/http.d/default.conf \
    && echo '    listen 0.0.0.0:80;' >> /etc/nginx/http.d/default.conf \
    && echo '    server_name _;' >> /etc/nginx/http.d/default.conf \
    && echo '    index index.php index.html;' >> /etc/nginx/http.d/default.conf \
    && echo '    root /var/www/html/public;' >> /etc/nginx/http.d/default.conf \
    && echo '    location = /health { return 200 "healthy\n"; add_header Content-Type text/plain; }' >> /etc/nginx/http.d/default.conf \
    && echo '    location / { try_files $uri $uri/ /index.php?$query_string; }' >> /etc/nginx/http.d/default.conf \
    && echo '    location ~ \.php$ { fastcgi_pass 127.0.0.1:9000; fastcgi_index index.php; include fastcgi_params; fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; }' >> /etc/nginx/http.d/default.conf \
    && echo '}' >> /etc/nginx/http.d/default.conf

# Create Nginx configuration template (PORT will be substituted at runtime)
RUN echo 'server {' > /etc/nginx/http.d/default.conf.template \
    && echo '    listen 0.0.0.0:PORT_PLACEHOLDER;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    server_name _;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    index index.php index.html;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    root /var/www/html/public;' >> /etc/nginx/http.d/default.conf.template \
    && echo '' >> /etc/nginx/http.d/default.conf.template \
    && echo '    # Simple health check endpoint (responds immediately, no PHP required)' >> /etc/nginx/http.d/default.conf.template \
    && echo '    location = /health {' >> /etc/nginx/http.d/default.conf.template \
    && echo '        access_log off;' >> /etc/nginx/http.d/default.conf.template \
    && echo '        return 200 "healthy\n";' >> /etc/nginx/http.d/default.conf.template \
    && echo '        add_header Content-Type text/plain;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    }' >> /etc/nginx/http.d/default.conf.template \
    && echo '' >> /etc/nginx/http.d/default.conf.template \
    && echo '    location / {' >> /etc/nginx/http.d/default.conf.template \
    && echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    }' >> /etc/nginx/http.d/default.conf.template \
    && echo '' >> /etc/nginx/http.d/default.conf.template \
    && echo '    location ~ \.php$ {' >> /etc/nginx/http.d/default.conf.template \
    && echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/http.d/default.conf.template \
    && echo '        fastcgi_index index.php;' >> /etc/nginx/http.d/default.conf.template \
    && echo '        include fastcgi_params;' >> /etc/nginx/http.d/default.conf.template \
    && echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/http.d/default.conf.template \
    && echo '        fastcgi_param PATH_INFO $fastcgi_path_info;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    }' >> /etc/nginx/http.d/default.conf.template \
    && echo '' >> /etc/nginx/http.d/default.conf.template \
    && echo '    location ~ /\.ht {' >> /etc/nginx/http.d/default.conf.template \
    && echo '        deny all;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    }' >> /etc/nginx/http.d/default.conf.template \
    && echo '}' >> /etc/nginx/http.d/default.conf.template

# Copy supervisor configuration
RUN echo '[supervisord]' > /etc/supervisord.conf \
    && echo 'nodaemon=true' >> /etc/supervisord.conf \
    && echo 'user=root' >> /etc/supervisord.conf \
    && echo 'environment=PORT="%(ENV_PORT)s"' >> /etc/supervisord.conf \
    && echo '' >> /etc/supervisord.conf \
    && echo '[program:php-fpm]' >> /etc/supervisord.conf \
    && echo 'command=php-fpm -F' >> /etc/supervisord.conf \
    && echo 'autostart=true' >> /etc/supervisord.conf \
    && echo 'autorestart=true' >> /etc/supervisord.conf \
    && echo 'priority=10' >> /etc/supervisord.conf \
    && echo 'stderr_logfile=/dev/stderr' >> /etc/supervisord.conf \
    && echo 'stderr_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo 'stdout_logfile=/dev/stdout' >> /etc/supervisord.conf \
    && echo 'stdout_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo '' >> /etc/supervisord.conf \
    && echo '[program:nginx]' >> /etc/supervisord.conf \
    && echo 'command=/bin/sh -c "if [ -f /etc/nginx/http.d/default.conf ]; then echo \"Nginx config found, starting...\" && cat /etc/nginx/http.d/default.conf | head -5; else echo \"WARNING: Nginx config not found, waiting...\" && sleep 2 && if [ -f /etc/nginx/http.d/default.conf ]; then echo \"Config now exists\"; else echo \"ERROR: Config still missing!\"; fi; fi && nginx -g \"daemon off;\""' >> /etc/supervisord.conf \
    && echo 'autostart=true' >> /etc/supervisord.conf \
    && echo 'autorestart=true' >> /etc/supervisord.conf \
    && echo 'priority=10' >> /etc/supervisord.conf \
    && echo 'startsecs=2' >> /etc/supervisord.conf \
    && echo 'stderr_logfile=/dev/stderr' >> /etc/supervisord.conf \
    && echo 'stderr_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo 'stdout_logfile=/dev/stdout' >> /etc/supervisord.conf \
    && echo 'stdout_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo '' >> /etc/supervisord.conf \
    && echo '[program:verify-nginx]' >> /etc/supervisord.conf \
    && echo 'environment=PORT="%(ENV_PORT)s"' >> /etc/supervisord.conf \
    && echo 'command=/bin/sh -c "sleep 8 && PORT=${PORT:-80} && echo \"=== Verifying Nginx ===\" && echo \"PORT: $PORT\" && echo \"Checking Nginx process...\" && ps aux | grep nginx | grep -v grep || echo \"Nginx process not found\" && echo \"Checking if Nginx is listening on port $PORT...\" && (netstat -tlnp 2>/dev/null | grep \":$PORT\" || ss -tlnp 2>/dev/null | grep \":$PORT\" || echo \"Port check: no match found\") && echo \"Checking all listening ports...\" && (netstat -tlnp 2>/dev/null | grep LISTEN || ss -tlnp 2>/dev/null | grep LISTEN || echo \"Cannot list ports\") && echo \"Testing health endpoint at http://127.0.0.1:$PORT/health...\" && curl -v -f http://127.0.0.1:$PORT/health 2>&1 && echo \"✓ Health endpoint is working!\" || (echo \"✗ Health endpoint failed\" && echo \"Checking Nginx config...\" && cat /etc/nginx/http.d/default.conf 2>/dev/null | head -30 || echo \"Config file not found\" && echo \"Checking Nginx error log...\" && tail -20 /var/log/nginx/error.log 2>/dev/null || echo \"Error log not found\")"' >> /etc/supervisord.conf \
    && echo 'autostart=true' >> /etc/supervisord.conf \
    && echo 'autorestart=false' >> /etc/supervisord.conf \
    && echo 'priority=8' >> /etc/supervisord.conf \
    && echo 'startsecs=0' >> /etc/supervisord.conf \
    && echo 'startretries=0' >> /etc/supervisord.conf \
    && echo 'stderr_logfile=/dev/stderr' >> /etc/supervisord.conf \
    && echo 'stderr_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo 'stdout_logfile=/dev/stdout' >> /etc/supervisord.conf \
    && echo 'stdout_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo '' >> /etc/supervisord.conf \
    && echo '[program:laravel-init]' >> /etc/supervisord.conf \
    && echo 'command=/bin/sh -c "sleep 5 && cd /var/www/html && /usr/local/bin/init-app.sh || echo Warning: Initialization had errors"' >> /etc/supervisord.conf \
    && echo 'autostart=true' >> /etc/supervisord.conf \
    && echo 'autorestart=false' >> /etc/supervisord.conf \
    && echo 'priority=5' >> /etc/supervisord.conf \
    && echo 'startsecs=0' >> /etc/supervisord.conf \
    && echo 'startretries=0' >> /etc/supervisord.conf \
    && echo 'stderr_logfile=/dev/stderr' >> /etc/supervisord.conf \
    && echo 'stderr_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo 'stdout_logfile=/dev/stdout' >> /etc/supervisord.conf \
    && echo 'stdout_logfile_maxbytes=0' >> /etc/supervisord.conf

# Expose port (Railway will set PORT env var at runtime)
EXPOSE 80

# Copy Railway initialization script
COPY railway/init-app.sh /usr/local/bin/init-app.sh
RUN chmod +x /usr/local/bin/init-app.sh

# Create startup script that configures Nginx before starting supervisor
# Use a separate script file for better debugging
RUN echo '#!/bin/sh' > /start.sh \
    && echo '' >> /start.sh \
    && echo 'echo "===================================" >&2' >> /start.sh \
    && echo 'echo "STARTUP SCRIPT RUNNING" >&2' >> /start.sh \
    && echo 'echo "===================================" >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo '# Get PORT from environment or default to 80' >> /start.sh \
    && echo 'PORT=${PORT:-80}' >> /start.sh \
    && echo 'echo "PORT environment variable: $PORT" >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo '# Remove any existing nginx configs' >> /start.sh \
    && echo 'echo "Removing existing Nginx configs..." >&2' >> /start.sh \
    && echo 'rm -f /etc/nginx/http.d/*.conf /etc/nginx/conf.d/*.conf 2>/dev/null || true' >> /start.sh \
    && echo '' >> /start.sh \
    && echo '# Verify template exists' >> /start.sh \
    && echo 'if [ ! -f /etc/nginx/http.d/default.conf.template ]; then' >> /start.sh \
    && echo '    echo "ERROR: Nginx template not found!" >&2' >> /start.sh \
    && echo '    exit 1' >> /start.sh \
    && echo 'fi' >> /start.sh \
    && echo 'echo "Template found, creating config..." >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo '# Configure Nginx with the correct port' >> /start.sh \
    && echo 'echo "Configuring Nginx to listen on 0.0.0.0:$PORT..." >&2' >> /start.sh \
    && echo 'sed "s/PORT_PLACEHOLDER/$PORT/" /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf' >> /start.sh \
    && echo 'CONFIG_EXIT=$?' >> /start.sh \
    && echo 'if [ $CONFIG_EXIT -ne 0 ]; then' >> /start.sh \
    && echo '    echo "ERROR: Failed to create Nginx config! Exit code: $CONFIG_EXIT" >&2' >> /start.sh \
    && echo '    exit 1' >> /start.sh \
    && echo 'fi' >> /start.sh \
    && echo 'echo "✓ Nginx config created" >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo '# Verify config file exists' >> /start.sh \
    && echo 'if [ ! -f /etc/nginx/http.d/default.conf ]; then' >> /start.sh \
    && echo '    echo "ERROR: Nginx config file does not exist after creation!" >&2' >> /start.sh \
    && echo '    ls -la /etc/nginx/http.d/ >&2 || echo "Cannot list directory" >&2' >> /start.sh \
    && echo '    exit 1' >> /start.sh \
    && echo 'fi' >> /start.sh \
    && echo 'echo "Config file exists, showing listen directive:" >&2' >> /start.sh \
    && echo 'grep "listen" /etc/nginx/http.d/default.conf >&2 || echo "WARNING: listen directive not found!" >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo '# Test Nginx configuration' >> /start.sh \
    && echo 'echo "Testing Nginx configuration..." >&2' >> /start.sh \
    && echo 'nginx -t >&2' >> /start.sh \
    && echo 'NGINX_TEST_EXIT=$?' >> /start.sh \
    && echo 'if [ $NGINX_TEST_EXIT -ne 0 ]; then' >> /start.sh \
    && echo '    echo "ERROR: Nginx config test failed! Exit code: $NGINX_TEST_EXIT" >&2' >> /start.sh \
    && echo '    echo "Config contents:" >&2' >> /start.sh \
    && echo '    cat /etc/nginx/http.d/default.conf >&2' >> /start.sh \
    && echo '    exit 1' >> /start.sh \
    && echo 'fi' >> /start.sh \
    && echo 'echo "✓ Nginx config is valid" >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo 'echo "Final config summary:" >&2' >> /start.sh \
    && echo 'grep "listen" /etc/nginx/http.d/default.conf >&2' >> /start.sh \
    && echo 'grep "location.*health" /etc/nginx/http.d/default.conf >&2' >> /start.sh \
    && echo '' >> /start.sh \
    && echo 'echo "Starting supervisord..." >&2' >> /start.sh \
    && echo 'cd /var/www/html' >> /start.sh \
    && echo 'exec /usr/bin/supervisord -c /etc/supervisord.conf' >> /start.sh \
    && chmod +x /start.sh

# Start supervisor
# Use shell form to ensure environment variables are available
CMD ["/bin/sh", "/start.sh"]

