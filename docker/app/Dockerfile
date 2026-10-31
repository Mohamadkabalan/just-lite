# Local stand-in for the Hostinger production server.
#
# Production runs LiteSpeed, which reads Apache .htaccess files, with the
# project root as the document root (the root .htaccess rewrites to public/).
# Apache with mod_rewrite and AllowOverride All behaves the same way here.
ARG PHP_VERSION=8.3
FROM php:${PHP_VERSION}-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip mariadb-client \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
        libicu-dev libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath exif gd intl mbstring mysqli opcache pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite headers expires \
    && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint

WORKDIR /var/www/html

ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]
