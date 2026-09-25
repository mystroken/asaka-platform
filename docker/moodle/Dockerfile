# Moodle 5.2 (puis 5.3 LTS) — image de développement.
# Le code Moodle est dans l'image ; seuls les plugins Asaka sont montés depuis le dépôt.
FROM php:8.3-apache-bookworm

ARG MOODLE_BRANCH=MOODLE_502_STABLE

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip postgresql-client \
        libpq-dev libicu-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libwebp-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pgsql intl zip gd soap exif opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/conf.d/moodle.ini
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers

# Code Moodle (5.1+ : racine web = public/, dépendances Composer obligatoires).
RUN git clone --depth 1 --branch "${MOODLE_BRANCH}" https://github.com/moodle/moodle.git /var/www/moodle \
    && cd /var/www/moodle \
    && composer install --no-dev --classmap-authoritative --no-interaction --no-progress \
    && mkdir -p /var/www/moodledata \
    && chown -R www-data:www-data /var/www/moodledata

COPY entrypoint.sh /usr/local/bin/asaka-moodle-entrypoint
COPY config.php /var/www/moodle/config.php
RUN chmod +x /usr/local/bin/asaka-moodle-entrypoint

WORKDIR /var/www/moodle
ENTRYPOINT ["asaka-moodle-entrypoint"]
CMD ["apache2-foreground"]
