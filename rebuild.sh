./vendor/bin/sail down -v
./vendor/bin/sail up -d
sleep 5
./vendor/bin/sail artisan migrate

# Build CSS assets
npm run build

# Clear caches
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear

php artisan serve
