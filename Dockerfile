# ──────────────────────────────────────────────
# Stage 1: Build frontend assets
# ──────────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit

COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY public ./public

RUN npm run build

# ──────────────────────────────────────────────
# Stage 2: Install PHP dependencies
# ──────────────────────────────────────────────
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ──────────────────────────────────────────────
# Stage 3: Production image
# ──────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpq \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    icu-libs \
    oniguruma \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        postgresql-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
        oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
        gd \
        bcmath \
        intl \
        opcache \
    && apk del .build-deps \
    && rm -rf /tmp/*

# PHP-FPM config
COPY docker/prod/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/prod/php.ini /usr/local/etc/php/conf.d/custom.ini

# Nginx config
COPY docker/prod/nginx.conf /etc/nginx/nginx.conf

# Supervisor config
COPY docker/prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Entrypoint
COPY docker/prod/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html

# Copy application
COPY --from=vendor /app .
COPY --from=assets /app/public/build ./public/build

# Storage & cache dirs
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
