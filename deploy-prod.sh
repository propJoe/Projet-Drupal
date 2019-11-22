#!/usr/bin/env bash

# Récupérer tout le code source.
git pull origin master

# Récupérer les librairies en dehors de la rubrique de dev
composer install --no-dev

# Vider le cache
drush cr

# Met à jour la base drupal
drush updb -y

# Exporter les config de l'environnement de production
drush csex prod -y

# Importe les config
drush cim -y

# Vider le cache
drush cr
