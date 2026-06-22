#!/usr/bin/env bash
set -euo pipefail

# ─────────────────────────────────────────────────────────────
# Fiesta Forms — Automated Deploy Script
# Usage:  bash deploy.sh [--seed] [--port 8080]
# ─────────────────────────────────────────────────────────────

REPO_URL="https://github.com/marlon0205/Fiesta-Forms.git"
APP_DIR="Fiesta-Forms"
SEED=false
PORT=80

# ── Parse args ───────────────────────────────────────────────
while [[ $# -gt 0 ]]; do
  case $1 in
    --seed) SEED=true; shift ;;
    --port) PORT="$2"; shift 2 ;;
    *) echo "Unknown option: $1"; exit 1 ;;
  esac
done

# ── Colors ───────────────────────────────────────────────────
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info()    { echo -e "${GREEN}[✓]${NC} $*"; }
warn()    { echo -e "${YELLOW}[!]${NC} $*"; }
error()   { echo -e "${RED}[✗]${NC} $*"; exit 1; }
step()    { echo -e "\n${YELLOW}──── $* ────${NC}"; }

# ── Prerequisite check ───────────────────────────────────────
step "Checking prerequisites"

command -v git    >/dev/null 2>&1 || error "git is required but not installed."
command -v docker >/dev/null 2>&1 || error "docker is required but not installed."

# Support both 'docker compose' (v2) and 'docker-compose' (v1)
if docker compose version >/dev/null 2>&1; then
  DC="docker compose"
elif command -v docker-compose >/dev/null 2>&1; then
  DC="docker-compose"
else
  error "docker compose (v2) or docker-compose (v1) is required but not found."
fi

docker info >/dev/null 2>&1 || error "Docker daemon is not running. Start Docker first."
info "git, docker, docker compose — all present"

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

# ── Generate APP_KEY (base64:…) ──────────────────────────────
generate_key() {
  if command -v php >/dev/null 2>&1; then
    php -r "echo 'base64:' . base64_encode(random_bytes(32));"
  else
    echo "base64:$(dd if=/dev/urandom bs=32 count=1 2>/dev/null | base64 | tr -d '\n')"
  fi
}

# ── .env setup ───────────────────────────────────────────────
step "Environment configuration"

if [ ! -f .env ]; then
  APP_KEY=$(generate_key)
  DB_PASS="$(LC_ALL=C tr -dc 'A-Za-z0-9!@#%^&*' </dev/urandom | head -c 24)"

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

  info ".env created with generated APP_KEY and random DB password"
else
  warn ".env already exists — skipping generation"
  # Ensure APP_PORT is set correctly
  if ! grep -q "^APP_PORT=" .env; then
    echo "APP_PORT=${PORT}" >> .env
  fi
fi

# Export vars so docker-compose can read them
set -a
# shellcheck disable=SC1091
source .env
set +a

# ── Build ─────────────────────────────────────────────────────
step "Building Docker image (this may take a few minutes)"
$DC -f docker-compose-prod.yml build --no-cache
info "Build complete"

# ── Start services ────────────────────────────────────────────
step "Starting services"
$DC -f docker-compose-prod.yml up -d
info "Containers started"

# ── Wait for app to be healthy ───────────────────────────────
step "Waiting for application to be ready"
RETRIES=30
until curl -sf "http://localhost:${PORT}" >/dev/null 2>&1 || [ $RETRIES -eq 0 ]; do
  RETRIES=$((RETRIES - 1))
  echo -n "."
  sleep 3
done
echo ""

if [ $RETRIES -eq 0 ]; then
  warn "App did not respond on port ${PORT} within 90s — check logs:"
  warn "  $DC -f docker-compose-prod.yml logs app"
else
  info "App is responding"
fi

# ── Optional seed ─────────────────────────────────────────────
if [ "$SEED" = true ]; then
  step "Seeding database"
  APP_CONTAINER=$($DC -f docker-compose-prod.yml ps -q app)
  docker exec "$APP_CONTAINER" php artisan migrate:fresh --seed --force --no-interaction
  info "Database seeded"
fi

# ── Done ──────────────────────────────────────────────────────
echo ""
echo -e "${GREEN}═══════════════════════════════════════════${NC}"
echo -e "${GREEN}  Fiesta Forms is running!${NC}"
echo -e "${GREEN}  URL:  http://localhost:${PORT}${NC}"
if [ "$SEED" = true ]; then
echo -e "${GREEN}  admin@admin.de       / admin${NC}"
echo -e "${GREEN}  customer@customer.de / customer${NC}"
echo -e "${GREEN}  guest@guest.de       / guest${NC}"
fi
echo -e "${GREEN}═══════════════════════════════════════════${NC}"
echo ""
echo "  Logs:  $DC -f ${APP_DIR}/docker-compose-prod.yml logs -f"
echo "  Stop:  $DC -f ${APP_DIR}/docker-compose-prod.yml down"
echo ""
