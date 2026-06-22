#!/usr/bin/env bash
set -euo pipefail

# ─────────────────────────────────────────────────────────────
# Fiesta Forms — Automated Deploy Script
# Requires: bash, curl (git + docker auto-installed on Linux)
# Usage:  bash deploy.sh [--seed] [--port 8080]
#
# Everything runs inside Docker — no PHP/Composer/Node needed on host.
# Image is built locally, never pushed to any registry.
# ─────────────────────────────────────────────────────────────

REPO_URL="https://github.com/marlon0205/Fiesta-Forms.git"
APP_DIR="Fiesta-Forms"
IMAGE_NAME="fiesta-forms"
NETWORK="fiesta-net"
DB_CONTAINER="fiesta-db"
APP_CONTAINER="fiesta-app"
SEED=false
PORT=80

while [[ $# -gt 0 ]]; do
  case $1 in
    --seed) SEED=true; shift ;;
    --port) PORT="$2"; shift 2 ;;
    *) echo "Unknown option: $1"; exit 1 ;;
  esac
done

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info()  { echo -e "${GREEN}[✓]${NC} $*"; }
warn()  { echo -e "${YELLOW}[!]${NC} $*"; }
error() { echo -e "${RED}[✗]${NC} $*"; exit 1; }
step()  { echo -e "\n${YELLOW}──── $* ────${NC}"; }

OS="$(uname -s)"

# ── Install git if missing ───────────────────────────────────
step "Checking git"
if ! command -v git >/dev/null 2>&1; then
  warn "git not found — installing..."
  if [ "$OS" = "Linux" ]; then
    if command -v apt-get >/dev/null 2>&1; then
      sudo apt-get update -qq && sudo apt-get install -y -qq git
    elif command -v dnf >/dev/null 2>&1; then
      sudo dnf install -y git
    elif command -v yum >/dev/null 2>&1; then
      sudo yum install -y git
    else
      error "Cannot auto-install git. Install it manually."
    fi
  else
    error "git not found. Install it manually."
  fi
fi
info "git $(git --version | awk '{print $3}')"

# ── Install Docker if missing ────────────────────────────────
step "Checking Docker"
if ! command -v docker >/dev/null 2>&1; then
  if [ "$OS" != "Linux" ]; then
    error "Docker not found. Install Docker Desktop for $OS: https://docs.docker.com/get-docker/"
  fi
  warn "Docker not found — installing via official script..."
  curl -fsSL https://get.docker.com | sudo sh
  sudo systemctl enable --now docker
  DOCKER="sudo docker"
else
  DOCKER="docker"
fi

if ! $DOCKER info >/dev/null 2>&1; then
  if [ "$OS" = "Linux" ]; then
    warn "Docker daemon not running — starting..."
    sudo systemctl start docker
    sleep 3
  fi
  $DOCKER info >/dev/null 2>&1 || error "Docker daemon failed to start."
fi
info "Docker $($DOCKER --version | awk '{print $3}' | tr -d ',')"

# ── Clone or update ──────────────────────────────────────────
step "Repository"
if [ -d "$APP_DIR/.git" ]; then
  warn "Directory '$APP_DIR' exists — pulling latest changes"
  git -C "$APP_DIR" pull --ff-only
else
  git clone "$REPO_URL" "$APP_DIR"
  info "Cloned into $APP_DIR"
fi
cd "$APP_DIR"

# ── Generate secrets (pipefail-safe, no external tools) ──────
random_b64_32() { dd if=/dev/urandom bs=32 count=1 2>/dev/null | base64 | tr -d '\n'; }
random_alnum()  { dd if=/dev/urandom bs=256 count=1 2>/dev/null | base64 | tr -dc 'A-Za-z0-9' | dd bs=1 count=32 2>/dev/null; }

# ── .env setup ───────────────────────────────────────────────
step "Environment configuration"
if [ ! -f .env ]; then
  APP_KEY="base64:$(random_b64_32)"
  DB_PASS="$(random_alnum)"
  cat > .env <<EOF
APP_ENV=production
APP_DEBUG=false
APP_KEY=${APP_KEY}
APP_URL=http://localhost:${PORT}
APP_PORT=${PORT}

DB_DATABASE=fiesta_forms
DB_USERNAME=fiesta
DB_PASSWORD=${DB_PASS}
EOF
  info ".env created"
else
  warn ".env already exists — skipping generation"
fi

. .env
info "Secrets loaded"

# ── Stop & remove old containers + volume (keeps credentials in sync) ────────
step "Cleanup old containers"
$DOCKER rm -f "$APP_CONTAINER" "$DB_CONTAINER" 2>/dev/null && warn "Removed old containers" || true
$DOCKER volume rm fiesta-db-data 2>/dev/null && warn "Removed old DB volume (will be recreated)" || true

# ── Build image locally ───────────────────────────────────────
step "Building Docker image locally (3-8 min)"
echo "  PHP 8.4 + Composer + Node 20 + npm all install inside the image"
$DOCKER build \
  --target production \
  --tag "$IMAGE_NAME:latest" \
  .
info "Image built: $IMAGE_NAME:latest"

# ── Create network ───────────────────────────────────────────
$DOCKER network create "$NETWORK" 2>/dev/null || true

# ── Start PostgreSQL ─────────────────────────────────────────
step "Starting PostgreSQL 17"
$DOCKER run -d \
  --name "$DB_CONTAINER" \
  --network "$NETWORK" \
  --restart unless-stopped \
  -v fiesta-db-data:/var/lib/postgresql/data \
  -e POSTGRES_DB="$DB_DATABASE" \
  -e POSTGRES_USER="$DB_USERNAME" \
  -e POSTGRES_PASSWORD="$DB_PASSWORD" \
  postgres:17-alpine
info "PostgreSQL started"

# Wait for DB to be ready
echo -n "  Waiting for database"
for i in $(seq 1 20); do
  if $DOCKER exec "$DB_CONTAINER" pg_isready -q -U "$DB_USERNAME" 2>/dev/null; then
    echo " ready"
    break
  fi
  echo -n "."
  sleep 2
done

# ── Start App ─────────────────────────────────────────────────
step "Starting app"
$DOCKER run -d \
  --name "$APP_CONTAINER" \
  --network "$NETWORK" \
  --restart unless-stopped \
  -p "${PORT}:80" \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e APP_KEY="$APP_KEY" \
  -e APP_URL="http://localhost:${PORT}" \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST="$DB_CONTAINER" \
  -e DB_PORT=5432 \
  -e DB_DATABASE="$DB_DATABASE" \
  -e DB_USERNAME="$DB_USERNAME" \
  -e DB_PASSWORD="$DB_PASSWORD" \
  -e SESSION_DRIVER=database \
  -e CACHE_STORE=database \
  -e QUEUE_CONNECTION=database \
  -e LOG_CHANNEL=stderr \
  -e LOG_LEVEL=error \
  "$IMAGE_NAME:latest"
info "App container started"

# ── Wait for app ──────────────────────────────────────────────
step "Waiting for application"
echo -n "  "
RETRIES=40
until curl -sf "http://localhost:${PORT}" >/dev/null 2>&1 || [ "$RETRIES" -eq 0 ]; do
  RETRIES=$((RETRIES - 1))
  echo -n "."
  sleep 3
done
echo ""
[ "$RETRIES" -eq 0 ] && warn "App not responding — check: $DOCKER logs $APP_CONTAINER" || info "App is up"

# ── Optional seed ─────────────────────────────────────────────
if [ "$SEED" = true ]; then
  step "Seeding database"
  $DOCKER exec "$APP_CONTAINER" php artisan migrate:fresh --seed --force --no-interaction
  info "Database seeded"
fi

# ── Done ──────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}═══════════════════════════════════════════${NC}"
echo -e "${GREEN}  Fiesta Forms is running!${NC}"
echo -e "${GREEN}  URL : http://localhost:${PORT}${NC}"
if [ "$SEED" = true ]; then
echo -e "${GREEN}  admin@admin.de       / admin${NC}"
echo -e "${GREEN}  customer@customer.de / customer${NC}"
echo -e "${GREEN}  guest@guest.de       / guest${NC}"
fi
echo -e "${GREEN}═══════════════════════════════════════════${NC}"
echo ""
echo "  Logs : $DOCKER logs -f $APP_CONTAINER"
echo "  Stop : $DOCKER rm -f $APP_CONTAINER $DB_CONTAINER"
echo ""
