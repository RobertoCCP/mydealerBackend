#!/bin/bash

# Copy .env file if it doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Run laravel commands
composer install &&
  php artisan key:generate &&
  php artisan jwt:secret
