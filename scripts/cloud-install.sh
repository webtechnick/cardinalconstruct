#!/usr/bin/env bash
# Cursor Cloud install/update — idempotent, runs on every agent boot.
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")/.."

# shellcheck source=cloud-ensure-docker.sh
source "$(dirname "${BASH_SOURCE[0]}")/cloud-ensure-docker.sh"
ensure_docker

# Elixir / Gulp 3 needs Node 10 — host npm on Cloud pods is too new.
# Frontend deps + builds go through `./cc npm|build` (node:10 tools profile).
# Committed public/css + public/js cover first paint without a host npm install.

if [[ ! -f vendor/autoload.php ]]; then
    docker_cmd run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" \
        -w /var/www/html laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs --no-interaction
fi
