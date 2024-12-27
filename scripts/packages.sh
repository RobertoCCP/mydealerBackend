#!/bin/bash

# Install all packages
apt update && apt install -y \
  git sudo \
  libzip-dev \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  unzip fish neofetch \
  fonts-powerline fonts-firacode &&
  docker-php-ext-install pdo gd zip pdo_mysql sockets &&
  pecl install xdebug &&
  docker-php-ext-enable xdebug

curl -sS https://starship.rs/install.sh >starship.sh &&
  chmod +x starship.sh &&
  ./starship.sh -y &&
  rm starship.sh
