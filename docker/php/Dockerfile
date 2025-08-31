FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libpng-dev libonig-dev libzip-dev \
 && docker-php-ext-install pdo pdo_pgsql pgsql gd zip \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
CMD ["php-fpm"]
