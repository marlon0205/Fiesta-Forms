# Fiesta-Forms

A survey creation and voting platform with a Cyber-themed UI.

**Laravel 12** · **PHP 8.4** · **PostgreSQL 17** · **Tailwind CSS** · **Docker (Sail)**

---

## What is Fiesta-Forms?

Fiesta-Forms lets admins build multi-question surveys that authenticated users can discover and vote on. Each vote is recorded per-question, rate-limited, and wrapped in a DB transaction. An admin panel provides full control over surveys, users, rewards, and category integrations.

---

## Features

- **Survey management** — create, edit, delete surveys with multiple questions and answer options
- **Voting system** — single vote per user, all questions required, rate-limited (10/min), DB transaction
- **Role-based access** — `admin`, `customer`, `guest` via Spatie Laravel Permission
- **Admin dashboard** — tabbed panel (Forms · Users · Rewards · Integrations) with sortable, paginated tables
- **User management** — admins can edit, reset passwords, and delete any user account
- **Rewards** — define point-threshold rewards tied to user vote counts
- **Category integration** — import product/service categories from a REST API or raw JSON (SSRF-protected)
- **REST API** — JSON endpoints for surveys and categories
- **Cyber UI** — responsive dark theme built with Tailwind CSS

---

## Quick Start

### 1. Clone
```bash
git clone <repo-url>
cd Fiesta-Forms
```

### 2. Environment
```bash
cp .env.example .env
```
Defaults in `.env` are pre-configured for Docker (PostgreSQL, Redis).

### 3. Install PHP dependencies
No local PHP needed — use the Sail bootstrap container:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Start containers
```bash
./vendor/bin/sail up -d
```

### 5. Generate key & build frontend
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 6. Run migrations & seed
```bash
./vendor/bin/sail artisan migrate --seed
```

App is now at **http://localhost**.

---

## Development

```bash
# Frontend hot reload (Vite)
./vendor/bin/sail npm run dev

# Reset database (fresh migrate + seed)
./vendor/bin/sail artisan migrate:fresh --seed
# or
./rebuild.sh

# Stop containers
./vendor/bin/sail down
```

Optional alias to avoid typing `./vendor/bin/sail` every time:
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

---

## Test Accounts

| Role | Email | Password |
|------|-------|----------|
| admin | admin@admin.de | admin |
| customer | customer@customer.de | customer |
| guest | guest@guest.de | guest |

---

## Routes Overview

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/dashboard` | Public home |
| GET | `/dashboard/explore` | Browse surveys |
| GET | `/dashboard/form/{id}` | Survey detail & voting |
| GET | `/dashboard/admin` | Admin panel *(auth + admin)* |
| GET | `/admin/survey/create` | Create survey *(admin)* |
| GET | `/admin/user/{id}/edit` | Edit user *(admin)* |
| GET | `/api/surveys/{id}` | Survey JSON |
| GET | `/api/demo/categories` | Categories JSON |

Full route reference: [`docs/routes.md`](docs/routes.md)

---

## Testing

```bash
# Run all tests
./vendor/bin/sail test

# Single file
./vendor/bin/sail test tests/Feature/Auth/AuthenticationTest.php

# Filter by name
./vendor/bin/sail test --filter=SurveyTest
```

Tests use Pest PHP with an in-memory SQLite database (`phpunit.xml`).

---

## Troubleshooting

**Port conflict (80 or 5432 already in use)**
```dotenv
# .env
APP_PORT=8080
FORWARD_DB_PORT=5433
```
Then restart: `./vendor/bin/sail up -d`

**Permission errors (Linux)**
```bash
sudo chown -R $USER:$USER .
```

---

## Docs

Full documentation lives in [`docs/`](docs/):
[Architecture](docs/architecture.md) · [Database](docs/database.md) · [Controllers](docs/controllers.md) · [Routes](docs/routes.md) · [API](docs/api.md) · [Frontend](docs/frontend.md)
