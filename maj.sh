composer install --no-interaction --prefer-dist --optimize-autoloader
php artisan down
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
php artisan up
