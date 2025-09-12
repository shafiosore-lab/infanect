FROM php:8.1-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y git zip unzip libonig-dev libzip-dev libpng-dev libjpeg-dev libpq-dev && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
