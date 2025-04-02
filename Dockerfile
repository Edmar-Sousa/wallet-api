FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY composer.json /var/www/html/composer.json

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html

COPY . /var/www/html

WORKDIR /var/www/html/public

EXPOSE 9000

CMD ["php-fpm"]
