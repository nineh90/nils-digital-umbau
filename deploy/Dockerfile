# nils-digital-web — Laufzeitabbild
#
# Aufbau bewusst identisch zum Ticketsystem: die PHP-Abhängigkeiten entstehen
# in einem eigenen Abbild, die Assets in einem Node-Abbild, und im Ergebnis
# landet beides zusammen mit dem Code in einem schlanken PHP-Apache. Weder Node
# noch Composer bleiben im ausgelieferten Abbild zurück.
#
# Gebaut wird auf dem VPS (deploy/deploy.sh).

# --- Stufe 1: PHP-Abhängigkeiten -------------------------------------------
#
# php:8.4-cli mit nachinstalliertem intl statt des fertigen composer-Abbilds:
# dort fehlt ext-intl, das filament/support voraussetzt. Hier ist es dieselbe
# PHP-Fassung wie in Stufe 3, die aufgelösten Pakete passen also zur Laufzeit.
FROM php:8.4-cli AS composer-deps

RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install -j"$(nproc)" intl zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

# --no-scripts: die Skripte rufen artisan auf, und artisan braucht eine
# vollständige Anwendung samt .env — beides gibt es zur Bauzeit nicht.
# package:discover und filament:upgrade laufen deshalb im Einstiegsskript.
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

# --- Stufe 2: Frontend ------------------------------------------------------
FROM node:22-alpine AS assets

WORKDIR /build

# Erst die Manifeste kopieren: solange sich package-lock.json nicht ändert,
# bleibt der npm-Schritt im Docker-Cache und der Deploy dauert Sekunden.
COPY package.json package-lock.json ./
RUN npm ci

COPY --from=composer-deps /app/vendor ./vendor
COPY resources ./resources
COPY app ./app
COPY vite.config.js ./

# Lädt auch Fredoka und Roboto Mono herunter und legt sie unter public/build
# ab — die Seite bindet keine Google Fonts ein.
RUN npm run build

# --- Stufe 3: Laufzeit ------------------------------------------------------
FROM php:8.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        libzip-dev \
        libpq-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        intl \
        zip \
        gd \
        opcache \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

# Der Dokumentenstamm zeigt auf public/. Damit liegen app/, storage/ und die
# .env außerhalb dessen, was Apache je ausliefern könnte.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
    && a2enmod rewrite headers expires

COPY deploy/php.ini /usr/local/etc/php/conf.d/nils-digital.ini

WORKDIR /var/www/html

COPY . .
COPY --from=composer-deps /app/vendor ./vendor
COPY --from=assets /build/public/build ./public/build

# public/uploads hängt im Betrieb an einem Volume. Das Verzeichnis muss
# trotzdem existieren und dem Apache-Benutzer gehören, sonst schlägt der erste
# Bild-Upload fehl, bevor das Volume angelegt ist.
RUN mkdir -p public/uploads \
    && chown -R www-data:www-data storage bootstrap/cache public/uploads \
    && chmod -R 775 storage bootstrap/cache public/uploads

COPY deploy/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
