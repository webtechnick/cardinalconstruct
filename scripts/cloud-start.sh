#!/usr/bin/env bash
# Cursor Cloud start — runs when an agent machine boots so Desktop tab works.
set -euo pipefail
cd "$(dirname "${BASH_SOURCE[0]}")/.."

# shellcheck source=cloud-ensure-docker.sh
source "$(dirname "${BASH_SOURCE[0]}")/cloud-ensure-docker.sh"
ensure_docker

if ! grep -qE '[[:space:]]cardinalconstruct\.test([[:space:]]|$)' /etc/hosts 2>/dev/null; then
    echo '127.0.0.1 cardinalconstruct.test' | sudo tee -a /etc/hosts >/dev/null
fi

./cc up
