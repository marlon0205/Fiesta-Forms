#!/bin/bash

# ==============================================================================
# Fiesta Forms Management Script
# ==============================================================================
# This script handles:
# 1. Initial Setup (Clone & Install)
# 2. Project Updates (Pull & Refresh)
# 3. Project Rebuild (Fresh start with Sail)
# 4. Start / Stop / Restart Sail services
# ==============================================================================

REPO_URL="https://github.com/marlon0205/Fiesta-Forms.git"
PROJECT_DIR="Fiesta-Forms"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Helper for headers
function print_header() {
    echo -e "\n${BLUE}======================================================================${NC}"
    echo -e "${BLUE}  $1 ${NC}"
    echo -e "${BLUE}======================================================================${NC}"
}

# Helper for success messages
function print_success() {
    echo -e "${GREEN}✔ $1${NC}"
}

# Helper for error messages
function print_error() {
    echo -e "${RED}✘ $1${NC}"
}

# Helper for status messages
function print_status() {
    echo -e "${YELLOW}➜ $1${NC}"
}

# Check for prerequisites
function check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker is not installed. Please install Docker to use Laravel Sail."
        exit 1
    fi
    
    if ! docker info &> /dev/null; then
        print_error "Docker is not running. Please start Docker."
        exit 1
    fi
}

# Handle Initial Setup/Clone
function initial_setup() {
    print_header "STARTING INITIAL SETUP"
    
    # 1. Clone if not in project dir
    if [[ $(basename "$PWD") != "$PROJECT_DIR" ]]; then
        if [ ! -d "$PROJECT_DIR" ]; then
            print_status "Cloning repository..."
            git clone "$REPO_URL" "$PROJECT_DIR"
        fi
        cd "$PROJECT_DIR" || { print_error "Failed to enter $PROJECT_DIR"; exit 1; }
    fi

    # 2. Environment file
    if [ ! -f .env ]; then
        print_status "Creating .env from .env.example..."
        cp .env.example .env
    fi

    # Ensure SQLite DB file exists
    if [ ! -f database/database.sqlite ]; then
        print_status "Creating database/database.sqlite..."
        touch database/database.sqlite
    fi

    # 3. Install dependencies (using a temporary docker container for composer)
    print_status "Installing Composer dependencies (this might take a moment)..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs

    # 4. Start Sail
    print_status "Starting Laravel Sail..."
    ./vendor/bin/sail up -d
    
    # Wait longer for services to be ready to avoid 'breakout detected' errors
    print_status "Waiting for services to be ready (20s)..."
    sleep 20

    # 5. Application Setup
    print_status "Generating application key..."
    ./vendor/bin/sail artisan key:generate
    
    print_status "Running migrations and seeding..."
    ./vendor/bin/sail artisan migrate --seed

    # 6. NPM Setup
    print_status "Installing NPM dependencies..."
    ./vendor/bin/sail npm install
    
    print_status "Building assets..."
    ./vendor/bin/sail npm run build

    print_success "Setup complete! You can now access the project at http://localhost"
}

# Start project
function start_project() {
    print_status "Starting Laravel Sail..."
    ./vendor/bin/sail up -d
    print_success "Services started."
}

# Stop project
function stop_project() {
    print_status "Stopping Laravel Sail..."
    ./vendor/bin/sail stop
    print_success "Services stopped."
}

# Restart project
function restart_project() {
    print_status "Restarting Laravel Sail..."
    ./vendor/bin/sail restart
    print_success "Services restarted."
}

# Handle Project Update
function update_project() {
    print_header "UPDATING PROJECT"
    
    print_status "Pulling latest changes..."
    git pull
    
    print_status "Updating Composer dependencies..."
    ./vendor/bin/sail composer install
    
    print_status "Updating NPM dependencies..."
    ./vendor/bin/sail npm install
    
    print_status "Building assets..."
    ./vendor/bin/sail npm run build
    
    print_status "Running migrations..."
    ./vendor/bin/sail artisan migrate
    
    print_success "Project updated successfully!"
}

# Handle Project Rebuild (based on rebuild.sh)
function rebuild_project() {
    print_header "REBUILDING PROJECT"
    
    print_status "Stopping Sail and removing volumes..."
    ./vendor/bin/sail down -v
    
    # Ensure SQLite DB file exists (in case it was deleted)
    if [ ! -f database/database.sqlite ]; then
        touch database/database.sqlite
    fi

    print_status "Starting Sail..."
    ./vendor/bin/sail up -d
    print_status "Waiting for services (15s)..."
    sleep 15
    
    print_status "Running fresh migrations with seeding..."
    ./vendor/bin/sail artisan migrate:fresh --seed
    
    print_status "Cleaning up build assets..."
    rm -rf public/build
    rm -rf public/hot
    
    print_status "Building assets..."
    ./vendor/bin/sail npm run build
    
    print_status "Clearing all caches..."
    ./vendor/bin/sail artisan view:clear
    ./vendor/bin/sail artisan cache:clear
    ./vendor/bin/sail artisan config:clear
    ./vendor/bin/sail artisan route:clear
    ./vendor/bin/sail artisan optimize:clear
    
    print_success "Project rebuilt successfully!"
}

# Main Menu
function show_menu() {
    # clear
    print_header "FIESTA FORMS MANAGEMENT"
    echo -e "1) ${GREEN}Setup/Install${NC} (Clone, dependencies, DB seed)"
    echo -e "2) ${YELLOW}Update${NC}        (Git pull, install, migrate)"
    echo -e "3) ${RED}Rebuild${NC}       (Sail down -v, migrate:fresh --seed)"
    echo -e "4) ${BLUE}Start${NC}         (Sail up -d)"
    echo -e "5) ${BLUE}Stop${NC}          (Sail stop)"
    echo -e "6) ${BLUE}Restart${NC}       (Sail restart)"
    echo -e "7) Exit"
    echo ""
    read -p "Choose an option [1-7]: " choice
    
    case $choice in
        1)
            initial_setup
            ;;
        2)
            update_project
            ;;
        3)
            rebuild_project
            ;;
        4)
            start_project
            ;;
        5)
            stop_project
            ;;
        6)
            restart_project
            ;;
        7)
            exit 0
            ;;
        *)
            print_error "Invalid option"
            sleep 1
            show_menu
            ;;
    esac
}

# Check prerequisites first
check_docker

# Ensure we are in the project root if we are in a subdirectory of it
if [[ "$PWD" == *"$PROJECT_DIR"* ]]; then
    # We are inside the project, let's find the root
    while [[ $(basename "$PWD") != "$PROJECT_DIR" && "$PWD" != "/" ]]; do
        cd ..
    done
fi

# If we are not in a git repo and not in the project dir, assume we need setup
if [ ! -d ".git" ] && [[ $(basename "$PWD") != "$PROJECT_DIR" ]]; then
    initial_setup
else
    show_menu
fi
