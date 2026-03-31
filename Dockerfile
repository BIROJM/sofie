# Utiliser une image officielle PHP 5.6 avec Apache (legacy)
FROM php:5.6-apache

# Fix pour les dépôts Debian Stretch (EOL) - Pointer vers les archives et ignorer les erreurs GPG
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && echo "Acquire::Check-Valid-Until \"false\";" > /etc/apt/apt.conf.d/99no-check-valid-until

# Configurer le fuseau horaire PHP
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/timezone.ini


# Installer les dépendances système et les extensions PHP
RUN apt-get update && apt-get install -y --allow-unauthenticated \
    libmcrypt-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) pdo_mysql mcrypt gd mbstring zip


# Installer Composer 1.x (compatible PHP 5.6)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.26
ENV COMPOSER_ALLOW_SUPERUSER 1




# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html/

# Configurer les droits pour Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activer le module de réécriture Apache
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80