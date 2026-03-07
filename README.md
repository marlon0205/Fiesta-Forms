# Fiesta Forms 📝

Fiesta Forms is a modern, interactive survey and form management application built with Laravel 12, PHP 8.4, and Tailwind CSS.

This project is designed to be set up and managed entirely through the included `fiesta.sh` management script, ensuring a seamless experience using Laravel Sail (Docker).

---

## 🚀 Quick Start

### 1. Prerequisites
Ensure you have the following installed and running on your system:
- **Git**
- **Docker** (and Docker Compose)

### 2. Installation
You can set up the entire project with a single command. Choose one of the two methods below:

#### Method A: Clone and Run (Recommended)
```bash
git clone https://github.com/marlon0205/Fiesta-Forms.git
cd Fiesta-Forms
chmod +x fiesta.sh
./fiesta.sh
```

#### Method B: Direct Script Execution
If you only have the `fiesta.sh` file, simply run it in an empty directory:
```bash
bash fiesta.sh
```
The script will automatically clone the repository and start the setup.

### 3. Choose "Setup/Install"
In the interactive menu, select **Option 1 (Setup/Install)**. This will:
- Create your `.env` file (configured for SQLite by default).
- Install all Composer and NPM dependencies.
- Start the Docker containers via Laravel Sail.
- Run migrations and seed the database with sample data.
- Build the frontend assets.

Once finished, you can access the app at: **[http://localhost](http://localhost)**

---

## 🛠 Project Management

Run `./fiesta.sh` at any time to access the management menu:

| Option | Action | Description |
| :--- | :--- | :--- |
| **1** | **Setup/Install** | Initial project scaffolding, dependency installation, and DB seeding. |
| **2** | **Update** | Pulls latest code from GitHub, updates dependencies, and runs migrations. |
| **3** | **Rebuild** | Performs a hard reset: wipes the DB, recreates containers, and re-seeds data. |
| **4** | **Start** | Boots up the Laravel Sail containers in the background. |
| **5** | **Stop** | Gracefully stops all project containers. |
| **6** | **Restart** | Restarts the Sail services. |

---

## ⚙️ Technical Details

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Tailwind CSS, Vite, Alpine.js
- **Environment:** Laravel Sail (Docker)
- **Database:** SQLite (Default for development) or PostgreSQL (Configurable in `.env`)

### Troubleshooting "Breakout Detected"
If you encounter a "container breakout detected" error during setup, simply wait a few seconds for Docker to fully initialize the mount points and run the command again. The script includes built-in wait times to minimize this.

---

## 📄 License
This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
