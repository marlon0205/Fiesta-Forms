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
Öffne die `.env` Datei und passe sie gegebenenfalls an. Die Standardkonfiguration (SQLite) ist bereits vorkonfiguriert.

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

---

## ▶️ Projekt-Verwaltung (Skripte)

Wir nutzen verschiedene Bash-Skripte, um die Docker-Umgebung und das Laravel-Setup zu steuern. Mache die Skripte vor der ersten Nutzung ausführbar: `chmod +x *.sh`.

| Skript | Beschreibung | Wann nutzen? |
| :--- | :--- | :--- |
| **`./start.sh`** | Startet die Container und leert Caches. | Normales Weiterarbeiten (ohne Datenverlust). |
| **`./stop.sh`** | Stoppt alle laufenden Container. | Wenn du Feierabend machst. |
| **`./rebuild.sh`** | Reset der DB, Neustart der Container & **mit Testdaten** (`seed`). | Wenn du einen frischen Stand mit Beispieldaten brauchst. |
| **`./start_empty.sh`** | Reset der DB, Neustart der Container & **ohne Testdaten**. | Wenn du eine komplett leere Datenbank zum Testen willst. |

### Schnellstart nach der Einrichtung:
Um das Projekt zum ersten Mal mit Testdaten zu befüllen und zu starten:
```bash
chmod +x *.sh
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
