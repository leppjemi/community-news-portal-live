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

# Create Nginx configuration template (PORT will be substituted at runtime)
RUN echo 'server {' > /etc/nginx/http.d/default.conf.template \
    && echo '    listen PORT_PLACEHOLDER;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    index index.php index.html;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    root /var/www/html/public;' >> /etc/nginx/http.d/default.conf.template \
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
    && echo '    }' >> /etc/nginx/http.d/default.conf.template \
    && echo '' >> /etc/nginx/http.d/default.conf.template \
    && echo '    location ~ /\.ht {' >> /etc/nginx/http.d/default.conf.template \
    && echo '        deny all;' >> /etc/nginx/http.d/default.conf.template \
    && echo '    }' >> /etc/nginx/http.d/default.conf.template \
    && echo '}' >> /etc/nginx/http.d/default.conf.template

# Copy supervisor configuration
RUN echo '[supervisord]' > /etc/supervisord.conf \
    && echo 'nodaemon=true' >> /etc/supervisord.conf \
    && echo '' >> /etc/supervisord.conf \
    && echo '[program:php-fpm]' >> /etc/supervisord.conf \
    && echo 'command=php-fpm' >> /etc/supervisord.conf \
    && echo 'autostart=true' >> /etc/supervisord.conf \
    && echo 'autorestart=true' >> /etc/supervisord.conf \
    && echo 'stderr_logfile=/dev/stderr' >> /etc/supervisord.conf \
    && echo 'stderr_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo 'stdout_logfile=/dev/stdout' >> /etc/supervisord.conf \
    && echo 'stdout_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo '' >> /etc/supervisord.conf \
    && echo '[program:nginx]' >> /etc/supervisord.conf \
    && echo 'command=nginx -g "daemon off;"' >> /etc/supervisord.conf \
    && echo 'autostart=true' >> /etc/supervisord.conf \
    && echo 'autorestart=true' >> /etc/supervisord.conf \
    && echo 'stderr_logfile=/dev/stderr' >> /etc/supervisord.conf \
    && echo 'stderr_logfile_maxbytes=0' >> /etc/supervisord.conf \
    && echo 'stdout_logfile=/dev/stdout' >> /etc/supervisord.conf \
    && echo 'stdout_logfile_maxbytes=0' >> /etc/supervisord.conf

# Expose port (Railway will set PORT env var at runtime)
EXPOSE 80

# Create startup script
RUN echo '#!/bin/sh' > /start.sh \
    && echo 'set -e' >> /start.sh \
    && echo 'PORT=${PORT:-80}' >> /start.sh \
    && echo 'sed "s/PORT_PLACEHOLDER/${PORT}/" /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf' >> /start.sh \
    && echo 'cd /var/www/html' >> /start.sh \
    && echo 'php artisan config:cache || true' >> /start.sh \
    && echo 'php artisan route:cache || true' >> /start.sh \
    && echo 'php artisan view:cache || true' >> /start.sh \
    && echo 'php artisan migrate --force || true' >> /start.sh \
    && echo 'exec /usr/bin/supervisord -c /etc/supervisord.conf' >> /start.sh \
    && chmod +x /start.sh

# Start supervisor
CMD ["/start.sh"]

