#!/bin/sh
php artisan migrate --force
php artisan db:seed --force
exec apache2-foreground
