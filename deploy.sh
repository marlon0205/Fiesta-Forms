#!/usr/bin/env bash
set -euo pipefail

# ─────────────────────────────────────────────────────────────
# Fiesta Forms — Automated Deploy Script
# Requires: nothing (installs git + docker automatically on Linux)
# Usage:  bash deploy.sh [--seed] [--port 8080]
#
# What runs INSIDE Docker (no host install needed):
#   PHP 8.4, Composer, Node 20, npm, PostgreSQL 17, Redis, Nginx
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
    elif command -v yum >/dev/null 2>&1; then
      sudo yum install -y git
    elif command -v dnf >/dev/null 2>&1; then
      sudo dnf install -y git
    else
      error "Cannot install git automatically. Please install git manually."
    fi
  elif [ "$OS" = "Darwin" ]; then
    error "git not found. Install via: xcode-select --install"
  else
    error "Cannot install git automatically on $OS. Please install git manually."
  fi
fi
info "git $(git --version | awk '{print $3}')"

# ── Install Docker if missing ────────────────────────────────
step "Checking Docker"
if ! command -v docker >/dev/null 2>&1; then
  warn "Docker not found — installing via official script..."
  if [ "$OS" != "Linux" ]; then
    error "Auto-install only works on Linux. Download Docker Desktop for $OS from https://docs.docker.com/get-docker/"
  fi
  curl -fsSL https://get.docker.com | sudo sh
  sudo systemctl enable --now docker
  # Add current user to docker group so sudo isn't needed for docker commands
  if [ -n "${SUDO_USER:-}" ]; then
    sudo usermod -aG docker "$SUDO_USER"
  else
    sudo usermod -aG docker "$USER" 2>/dev/null || true
  fi
  warn "Docker installed. You may need to log out and back in for group permissions."
  warn "Continuing as root for this run..."
  DOCKER_CMD="sudo docker"
else
  DOCKER_CMD="docker"
fi

if ! $DOCKER_CMD info >/dev/null 2>&1; then
  if [ "$OS" = "Linux" ]; then
    warn "Docker daemon not running — starting..."
    sudo systemctl start docker
    sleep 3
    $DOCKER_CMD info >/dev/null 2>&1 || error "Docker daemon failed to start. Run: sudo systemctl status docker"
  else
    error "Docker is not running. Start Docker Desktop first."
  fi
fi
info "Docker $($DOCKER_CMD --version | awk '{print $3}' | tr -d ',')"

# ── Resolve docker compose command ──────────────────────────
if $DOCKER_CMD compose version >/dev/null 2>&1; then
  DC="$DOCKER_CMD compose"
elif command -v docker-compose >/dev/null 2>&1; then
  DC="docker-compose"
else
  warn "Docker Compose plugin not found — installing..."
  if command -v apt-get >/dev/null 2>&1; then
    sudo apt-get install -y -qq docker-compose-plugin
    DC="$DOCKER_CMD compose"
  else
    error "Could not install Docker Compose. Please install it manually: https://docs.docker.com/compose/install/"
  fi
fi
info "Compose: $DC"

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

# ── Random string helpers (pipefail-safe) ────────────────────
random_b64_32() {
  dd if=/dev/urandom bs=32 count=1 2>/dev/null | base64 | tr -d '\n'
}
random_alnum() {
  dd if=/dev/urandom bs=256 count=1 2>/dev/null | base64 | tr -dc 'A-Za-z0-9' | dd bs=1 count=32 2>/dev/null
}

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

  info ".env created with generated APP_KEY and random DB password"
else
  warn ".env already exists — skipping generation"
  if ! grep -q "^APP_PORT=" .env; then
    echo "APP_PORT=${PORT}" >> .env
  fi
fi

# Export vars so docker compose can read them
set -a
# shellcheck disable=SC1091
. .env
set +a

# ── Build ─────────────────────────────────────────────────────
step "Building Docker image"
echo "  (installs PHP 8.4, Composer, Node 20, npm, builds frontend — takes 3-8 min)"
$DC -f docker-compose-prod.yml build --no-cache
info "Build complete"

# ── Start services ────────────────────────────────────────────
step "Starting services (app + PostgreSQL)"
$DC -f docker-compose-prod.yml up -d
info "Containers started"

# ── Wait for app to be healthy ───────────────────────────────
step "Waiting for application to be ready"
RETRIES=40
echo -n "  "
until curl -sf "http://localhost:${PORT}" >/dev/null 2>&1 || [ $RETRIES -eq 0 ]; do
  RETRIES=$((RETRIES - 1))
  echo -n "."
  sleep 3
done
echo ""

if [ $RETRIES -eq 0 ]; then
  warn "App did not respond on port ${PORT} within 2 min."
  warn "Check logs: $DC -f docker-compose-prod.yml logs app"
else
  info "App is up and responding"
fi

# ── Optional seed ─────────────────────────────────────────────
if [ "$SEED" = true ]; then
  step "Seeding database with test accounts"
  APP_CONTAINER=$($DC -f docker-compose-prod.yml ps -q app)
  $DOCKER_CMD exec "$APP_CONTAINER" php artisan migrate:fresh --seed --force --no-interaction
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
echo -e "${GREEN}═══════════════════════════════────────────${NC}"
echo ""
echo "  Logs:  $DC -f $APP_DIR/docker-compose-prod.yml logs -f"
echo "  Stop:  $DC -f $APP_DIR/docker-compose-prod.yml down"
echo ""
