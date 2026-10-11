#!/usr/bin/env bash
# =====================================================
# CC - Cardinal Construct Docker Command Wrapper
# =====================================================
# Mirrors the shape of the other project CLIs (bai / tc / etp / dora).
# Sail-free: routes service-aware commands through docker compose exec.

set -e

cd "$(dirname "${BASH_SOURCE[0]}")"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m'
BOLD='\033[1m'

APP="app"

# Memoized: consulted several times per invocation and `docker info` is slow.
CC_FUSE_CLOUD=""

is_fuse_cloud() {
    if [[ -n "$CC_FUSE_CLOUD" ]]; then
        return "$CC_FUSE_CLOUD"
    fi

    CC_FUSE_CLOUD=1

    if [[ -f /etc/docker/daemon.json ]] && grep -q 'fuse-overlayfs' /etc/docker/daemon.json 2>/dev/null; then
        CC_FUSE_CLOUD=0
    elif docker info 2>/dev/null | grep -q 'fuse-overlayfs'; then
        CC_FUSE_CLOUD=0
    elif ! docker info >/dev/null 2>&1 && sudo -n docker info 2>/dev/null | grep -q 'fuse-overlayfs'; then
        # Only cloud pods with a root-only daemon get here. `sudo -n` keeps a
        # laptop from ever seeing a password prompt.
        CC_FUSE_CLOUD=0
    fi

    return "$CC_FUSE_CLOUD"
}

COMPOSE="docker compose"

resolve_compose() {
    local docker_bin="docker"

    if is_fuse_cloud; then
        export COMPOSE_FILE="${COMPOSE_FILE:-docker-compose.yml:compose.cloud.yaml}"
        if ! docker info >/dev/null 2>&1 && sudo -n docker info >/dev/null 2>&1; then
            docker_bin="sudo docker"
        fi
    fi

    if $docker_bin compose version >/dev/null 2>&1; then
        COMPOSE="$docker_bin compose"
    elif [[ "$docker_bin" == "docker" ]] && command -v docker-compose >/dev/null 2>&1; then
        COMPOSE="docker-compose"
    else
        echo -e "${RED}✖${NC} Docker Compose not found. Install Docker Desktop."
        exit 1
    fi
}

# Resolve now when Docker is already on PATH (laptops). Cloud first-boot may
# install Docker inside check_docker / cloud-prepare, then re-resolve.
if command -v docker >/dev/null 2>&1 || command -v docker-compose >/dev/null 2>&1; then
    resolve_compose
fi

cloud_prepare() {
    bash scripts/cloud-prepare.sh
}

cloud_post_up() {
    bash scripts/cloud-prepare.sh --post-up
}

info()    { echo -e "${BLUE}➜${NC} $1"; }
success() { echo -e "${GREEN}✔${NC} $1"; }
warning() { echo -e "${YELLOW}⚠${NC} $1"; }
error()   { echo -e "${RED}✖${NC} $1"; }
header()  { echo -e "\n${MAGENTA}${BOLD}$1${NC}\n"; }

check_docker() {
    if is_fuse_cloud; then
        # shellcheck source=scripts/cloud-ensure-docker.sh
        source "$(dirname "${BASH_SOURCE[0]}")/scripts/cloud-ensure-docker.sh"
        ensure_docker
        resolve_compose
        return
    fi

    if ! docker info > /dev/null 2>&1; then
        error "Docker is not running. Please start Docker Desktop first."
        exit 1
    fi
}

show_help() {
    echo -e "${CYAN}${BOLD}"
    echo "  ╔═══════════════════════════════════════════════════════════╗"
    echo "  ║            CC - Cardinal Construct CLI                    ║"
    echo "  ╚═══════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
    echo -e "${BOLD}Usage:${NC} ./cc <command> [args]"
    echo ""
    echo -e "${BOLD}Container lifecycle:${NC}"
    echo -e "  ${GREEN}up${NC}          Start containers (bootstraps .env + APP_KEY on first run)"
    echo -e "  ${GREEN}down${NC}        Stop and remove containers"
    echo -e "  ${GREEN}stop${NC}        Stop containers (keep them)"
    echo -e "  ${GREEN}start${NC}       Start stopped containers"
    echo -e "  ${GREEN}restart${NC}     Restart containers"
    echo -e "  ${GREEN}rebuild${NC}     Rebuild images from scratch, then up"
    echo -e "  ${GREEN}ps${NC} / ${GREEN}status${NC}  Show container status"
    echo -e "  ${GREEN}logs${NC} [svc]  Follow logs (all services, or one)"
    echo -e "  ${GREEN}shell${NC}       Bash into the app container"
    echo ""
    echo -e "${BOLD}Laravel:${NC}"
    echo -e "  ${YELLOW}artisan${NC} <cmd>  Run an artisan command"
    echo -e "  ${YELLOW}tinker${NC}        Open Tinker REPL"
    echo -e "  ${YELLOW}migrate${NC}       php artisan migrate"
    echo -e "  ${YELLOW}fresh${NC} [--yes] migrate:fresh --seed (drops all tables; confirm unless --yes)"
    echo -e "  ${YELLOW}seed${NC}          db:seed"
    echo -e "  ${YELLOW}cache${NC}         Clear config/route/view/app caches + Redis FLUSHALL"
    echo -e "  ${YELLOW}test${NC} [args]   Run the PHPUnit suite (SQLite :memory: testing DB)"
    echo -e "  ${YELLOW}check${NC}         Local quality gate: PHPUnit (fails fast)"
    echo ""
    echo -e "${BOLD}Tooling:${NC}"
    echo -e "  ${CYAN}composer${NC} <cmd> Run composer in the app container"
    echo -e "  ${CYAN}npm${NC} <cmd>      Run npm in the node helper (legacy elixir toolchain)"
    echo -e "  ${CYAN}build${NC}         Build frontend assets (npm run prod)"
    echo -e "  ${CYAN}dev${NC}           Watch & rebuild assets (npm run dev)"
    echo -e "  ${CYAN}mysql${NC}         MySQL CLI into the cardinalconstruct database"
    echo -e "  ${CYAN}redis${NC}         redis-cli"
    echo ""
    echo -e "${BOLD}Notes:${NC}"
    echo -e "  Anything else is forwarded into the ${GREEN}app${NC} container."
    echo -e "  Production deploys need the user's explicit go-ahead (no in-repo deploy yet)."
    echo -e "  On Cursor Cloud (fuse-overlayfs), ${GREEN}up${NC}/${GREEN}check${NC} auto-apply compose.cloud.yaml."
    echo ""
    echo -e "${BOLD}Local URL:${NC} ${GREEN}http://localhost${NC} (Cloud: ${GREEN}http://cardinalconstruct.test${NC})"
    echo ""
}

case "$1" in
    up)
        if is_fuse_cloud; then
            cloud_prepare
        fi
        check_docker
        if [ ! -f .env ]; then
            info "Creating .env from .env.example..."
            cp .env.example .env
        fi
        header "Starting Cardinal Construct..."
        $COMPOSE up -d --build
        # Code is bind-mounted, so fix storage perms on the host tree.
        $COMPOSE exec -T "$APP" chmod -R 777 storage bootstrap/cache 2>/dev/null || true
        # Generate APP_KEY if it's empty or still the example placeholder.
        CURRENT_KEY=$(grep -E '^APP_KEY=' .env | cut -d '=' -f2-)
        if [ -z "$CURRENT_KEY" ] || [ "$CURRENT_KEY" = "SomeRandomString" ]; then
            info "Generating application key..."
            $COMPOSE exec -T "$APP" php artisan key:generate \
                || warning "key:generate failed — run './cc composer install' first, then './cc artisan key:generate'."
        fi
        if is_fuse_cloud; then
            cloud_post_up
        fi
        success "Containers started!"
        if is_fuse_cloud; then
            echo -e "  ${CYAN}➜${NC} App: ${GREEN}http://cardinalconstruct.test${NC}"
        else
            echo -e "  ${CYAN}➜${NC} App: ${GREEN}http://localhost${NC}"
        fi
        ;;

    down)
        header "Stopping Cardinal Construct..."
        $COMPOSE down
        success "Containers stopped and removed."
        ;;

    stop)
        $COMPOSE stop
        success "Containers stopped."
        ;;

    start)
        check_docker
        $COMPOSE start
        success "Containers started."
        ;;

    restart)
        $COMPOSE restart
        success "Containers restarted."
        ;;

    rebuild)
        check_docker
        header "Rebuilding containers from scratch..."
        $COMPOSE down
        $COMPOSE build --no-cache
        $COMPOSE up -d
        success "Containers rebuilt!"
        ;;

    ps|status)
        $COMPOSE ps
        ;;

    logs)
        shift
        if [ $# -eq 0 ]; then
            $COMPOSE logs -f
        else
            $COMPOSE logs -f "$@"
        fi
        ;;

    shell)
        check_docker
        header "Opening shell in app container..."
        $COMPOSE exec "$APP" bash
        ;;

    artisan)
        check_docker
        shift
        $COMPOSE exec "$APP" php artisan "$@"
        ;;

    tinker)
        check_docker
        $COMPOSE exec "$APP" php artisan tinker
        ;;

    migrate)
        check_docker
        shift
        $COMPOSE exec "$APP" php artisan migrate "$@"
        ;;

    fresh)
        check_docker
        shift
        FORCE=0
        if [ "${1:-}" = "--yes" ] || [ "${1:-}" = "-y" ]; then
            FORCE=1
            shift
        fi
        if [ "$FORCE" -eq 1 ]; then
            $COMPOSE exec -T "$APP" php artisan migrate:fresh --seed "$@"
            success "Database refreshed and seeded!"
        else
            warning "migrate:fresh --seed will DROP ALL TABLES."
            read -p "Continue? (y/N) " -n 1 -r REPLY
            echo ""
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                $COMPOSE exec "$APP" php artisan migrate:fresh --seed "$@"
                success "Database refreshed and seeded!"
            else
                info "Aborted."
            fi
        fi
        ;;

    seed)
        check_docker
        shift
        $COMPOSE exec "$APP" php artisan db:seed "$@"
        ;;

    cache)
        check_docker
        header "Clearing caches..."
        $COMPOSE exec "$APP" php artisan cache:clear
        $COMPOSE exec "$APP" php artisan config:clear
        $COMPOSE exec "$APP" php artisan route:clear
        $COMPOSE exec "$APP" php artisan view:clear
        $COMPOSE exec redis redis-cli FLUSHALL
        success "Caches cleared."
        ;;

    test)
        check_docker
        shift
        header "Running PHPUnit..."
        $COMPOSE exec -T "$APP" ./vendor/bin/phpunit "$@"
        ;;

    check)
        if is_fuse_cloud; then
            cloud_prepare
        fi
        check_docker
        if is_fuse_cloud && ! $COMPOSE ps --status running --services 2>/dev/null | grep -qx app; then
            $COMPOSE up -d --build
            cloud_post_up
        fi
        header "Running local quality gate (PHPUnit)..."
        $COMPOSE exec -T "$APP" ./vendor/bin/phpunit
        success "All checks passed."
        ;;

    composer)
        check_docker
        shift
        $COMPOSE exec "$APP" composer "$@"
        ;;

    npm)
        check_docker
        shift
        $COMPOSE --profile tools run --rm node npm "$@"
        ;;

    build)
        check_docker
        header "Building frontend assets (npm run prod)..."
        $COMPOSE --profile tools run --rm node npm run prod
        success "Frontend assets built!"
        ;;

    dev)
        check_docker
        header "Watching frontend assets (npm run dev)..."
        info "Press Ctrl+C to stop watching."
        $COMPOSE --profile tools run --rm node npm run dev
        ;;

    mysql)
        check_docker
        $COMPOSE exec db mysql -u cardinalconstruct -ppassword cardinalconstruct
        ;;

    redis)
        check_docker
        $COMPOSE exec redis redis-cli
        ;;

    ""|help|--help|-h)
        show_help
        ;;

    *)
        # Forward unrecognized verbs into the app container so the CLI never blocks.
        check_docker
        $COMPOSE exec "$APP" "$@"
        ;;
esac
