# Utiliser une image officielle PHP avec Apache
FROM php:8.1-apache

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html/

# Configurer les droits pour Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activer le module de réécriture Apache
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80