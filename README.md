# Fiesta Forms 📝

Fiesta Forms ist eine moderne, interaktive Anwendung zur Verwaltung von Umfragen und Formularen, entwickelt mit Laravel 12, PHP 8.4 und Tailwind CSS.

## 🚀 Voraussetzungen

Für die lokale Entwicklung benötigst du folgende Software:

- **Docker** (Desktop oder Engine)
- **Composer** (PHP Dependency Manager)
- **Node.js** & **npm** (für Frontend-Assets)

## 🛠 Installation & Einrichtung

Folge diesen Schritten, um das Projekt zum ersten Mal einzurichten:

### 1. Repository klonen
```bash
git clone https://github.com/marlon0205/Fiesta-Forms
cd Fiesta-Forms
```

### 2. Umgebungsvariablen setzen
Kopiere die Beispiel-Konfiguration in eine aktive `.env` Datei:
```bash
cp .env.example .env
```
Öffne die `.env` Datei und passe sie gegebenenfalls an. Die Standardkonfiguration (SQLite) ist für die lokale Entwicklung bereits optimiert.

### 3. Abhängigkeiten installieren
Lade die PHP-Abhängigkeiten (inklusive Laravel Sail) herunter:
```bash
composer install
```

### 4. Application Key generieren
Erzeuge den Verschlüsselungsschlüssel für die Anwendung:
```bash
./vendor/bin/sail artisan key:generate
```
*Hinweis: Dies startet kurzzeitig einen Docker-Container via Sail.*

---

## ▶️ Projekt starten & Ausführen

Wir verwenden ein lokales Skript (`rebuild.sh`), um die Entwicklungsumgebung zu steuern.

### Das Rebuild-Skript
Das Skript führt einen kompletten Reset der Umgebung durch. Es erledigt folgende Aufgaben automatisch:
1.  Stoppt laufende Sail-Container.
2.  Startet die Container neu.
3.  Setzt die Datenbank zurück und befüllt sie mit Testdaten (`migrate:fresh --seed`).
4.  Baut die Frontend-Assets (CSS/JS).
5.  Leert alle Caches (View, Config, Route, etc.).

### Ausführen
Mache das Skript ausführbar (falls noch nicht geschehen) und starte es:

```bash
chmod +x rebuild.sh
./rebuild.sh
```

Sobald das Skript durchgelaufen ist, kannst du die Anwendung im Browser aufrufen:

👉 **[http://localhost](http://localhost)**

---

## ⚙️ Technische Details

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Tailwind CSS, Vite, Alpine.js
- **Umgebung:** Laravel Sail (Docker)
- **Datenbank:** SQLite (Standard für Entwicklung)
