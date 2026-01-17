./vendor/bin/sail down -v
./vendor/bin/sail up -d
sleep 5
./vendor/bin/sail artisan migrate
php artisan serve
