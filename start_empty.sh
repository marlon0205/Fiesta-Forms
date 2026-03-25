#!/bin/bash

echo "Fahre bestehende Container herunter und entferne Volumes (DB Reset)..."
./vendor/bin/sail down -v

echo "Starte neue Container..."
./vendor/bin/sail up -d
sleep 5

echo "Führe Datenbank-Migrationen aus (OHNE SEEDER)..."
./vendor/bin/sail artisan migrate:fresh

echo "Bereite Frontend-Assets vor..."
rm -rf public/build
rm -rf public/hot
./vendor/bin/sail npm run build

echo "Leere Caches..."
./vendor/bin/sail artisan optimize:clear

echo "Projekt leer gestartet."
