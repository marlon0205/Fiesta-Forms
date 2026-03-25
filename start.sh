#!/bin/bash

echo "Starte Laravel Sail Container..."
./vendor/bin/sail up -d

echo "Warte kurz, bis die Container bereit sind..."
sleep 5

echo "Leere Laravel Caches..."
./vendor/bin/sail artisan optimize:clear

echo "Projekt gestartet."
