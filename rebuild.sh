#!/bin/bash

./vendor/bin/sail down -v

./vendor/bin/sail up -d
sleep 5

./vendor/bin/sail artisan migrate:fresh --seed

rm -rf public/build
rm -rf public/hot

npm run build

./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan optimize:clear
