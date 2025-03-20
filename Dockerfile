FROM php:8.2-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    nodejs \
    npm \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite
RUN a2enmod ssl
RUN service apache2 restart

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install

RUN npm install

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
