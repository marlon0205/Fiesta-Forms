# Fiesta-Forms

Willkommen bei **Fiesta-Forms**. Dieses Projekt ist eine Laravel-Anwendung, die Docker (via Laravel Sail) für die Entwicklungsumgebung nutzt.

## 📋 Voraussetzungen

Bevor du startest, stelle sicher, dass folgende Software installiert ist:

### Für Linux & macOS
- [Docker Engine](https://docs.docker.com/engine/install/) & [Docker Compose](https://docs.docker.com/compose/install/)
- Git

### Für Windows
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [WSL2](https://learn.microsoft.com/de-de/windows/wsl/install) (Windows Subsystem for Linux)
    - *Empfehlung:* Führe alle Befehle innerhalb einer WSL2-Distro (z. B. Ubuntu) aus, um Performance-Probleme zu vermeiden.

---

## 🚀 Installation & Setup

Folge diesen Schritten, um das Projekt lokal zum Laufen zu bringen.

### 1. Repository klonen
```bash
git clone <DEIN-REPO-URL>
cd Fiesta-Forms
```

### 2. Umgebungsvariablen konfigurieren
Kopiere die Beispiel-Konfiguration:
```bash
cp .env.example .env
```
*Hinweis:* Die Standard-Einstellungen in der `.env` sind bereits für die Docker-Umgebung (Sail) vorkonfiguriert (PostgreSQL, Redis etc.).

### 3. Abhängigkeiten installieren
Da wir Sail nutzen, können wir einen kleinen Container verwenden, um die PHP-Abhängigkeiten zu installieren, ohne PHP lokal installiert haben zu müssen:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Docker Container starten (Sail)
Starte die Anwendung im Hintergrund:
```bash
./vendor/bin/sail up -d
```
*Dies kann beim ersten Mal einige Minuten dauern, da die Images gebaut werden.*

### 5. Key generieren & Frontend bauen
Sobald die Container laufen:

```bash
# App Key generieren
./vendor/bin/sail artisan key:generate

# Node-Abhängigkeiten installieren und Assets bauen
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 6. Datenbank einrichten
Führe die Migrationen und Seeder aus, um die Datenbank zu füllen:
```bash
./vendor/bin/sail artisan migrate --seed
```

---

## 🏁 Starten & Nutzen

Die Anwendung ist nun unter folgender Adresse erreichbar:

👉 **http://localhost**

### Entwicklung (Hot Reloading)
Für die Frontend-Entwicklung (Vite) starte den Dev-Server:
```bash
./vendor/bin/sail npm run dev
```

### Container stoppen
```bash
./vendor/bin/sail down
```

---

## 🛠 Nützliche Befehle & Skripte

### Sail Alias (Optional)
Um nicht immer `./vendor/bin/sail` tippen zu müssen, kannst du einen Alias setzen:
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```
Dann kannst du Befehle einfach so nutzen: `sail artisan ...`

### Datenbank zurücksetzen
Im Projekt liegt ein Hilfsskript `reset-db.sh`, das die Datenbank komplett löscht, neu aufbaut und mit Testdaten füllt.

**Nutzung (Linux/Mac/WSL):**
```bash
chmod +x reset-db.sh  # Einmalig ausführbar machen
./reset-db.sh
```

### Tests ausführen
```bash
./vendor/bin/sail test
```

---

## 🐛 Troubleshooting

**Berechtigungsprobleme (Linux):**
Falls du Schreibrechte-Fehler bekommst, stelle sicher, dass dein User Eigentümer der Dateien ist:
```bash
sudo chown -R $USER:$USER .
```

**Port belegt:**
Falls Port 80 oder 5432 (Postgres) belegt sind, kannst du diese in der `.env` Datei ändern:
```dotenv
APP_PORT=8080
FORWARD_DB_PORT=5433
```
Danach `sail up -d` neu ausführen.
