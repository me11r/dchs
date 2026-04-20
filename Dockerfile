# Stage 1: Install dependencies using official Composer image
FROM composer:1 as build-stage
WORKDIR /app
COPY composer.json composer.lock ./
COPY . .
# Full install including autoloader generation (no scripts to avoid env issues)
RUN composer install --no-interaction --no-plugins --no-scripts --ignore-platform-reqs && \
    composer dump-autoload --optimize --no-scripts

# Stage 2: Final application image
FROM laradock/php-fpm:2.2-7.2

RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's/security.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update
    
RUN apt-get install -y libmcrypt-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev zip libpq-dev default-mysql-client wget libzip-dev  \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pcntl \
    && pecl install mcrypt-1.0.2 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install gd zip \
    && pecl install xdebug-2.7.1 \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-enable pcntl

WORKDIR /var/www

# Copy application code
COPY . .

# Copy fully-built vendor from Stage 1 (overrides any empty vendor in COPY above)
COPY --from=build-stage /app/vendor /var/www/vendor

# Fix permissions so Laravel can write to storage and cache
RUN mkdir -p /var/www/storage/logs \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Final config copy
COPY ./.docker/php/php-fpm.conf /usr/local/etc/php-fpm.conf
