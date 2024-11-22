# Utilizza l'immagine ufficiale di PHP con Apache
FROM php:8.2-apache

# Aggiorna il sistema e installa i pacchetti necessari
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Abilita il modulo Apache mod_rewrite
RUN a2enmod rewrite

# Installa Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Imposta la directory di lavoro
WORKDIR /var/www/html

# Copia il file composer.json e composer.lock
COPY composer.json composer.lock ./

# Installa le dipendenze PHP
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copia tutti i file del progetto nella directory di lavoro
COPY . .

# Esegui nuovamente composer install per generare l'autoloader
RUN composer install --no-interaction --prefer-dist

# Copia il file di configurazione di Apache
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Imposta i permessi corretti
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Esponi la porta 80
EXPOSE 80

# Avvia Apache in primo piano
CMD ["apache2-foreground"]
