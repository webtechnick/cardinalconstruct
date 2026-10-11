#!/usr/bin/env bash
# Shared Docker bootstrap for Cursor Cloud scripts.
# Source from cloud-install.sh, cloud-start.sh, and cloud-prepare.sh.

docker_cmd() {
    if docker info >/dev/null 2>&1; then
        docker "$@"

        return
    fi

    if sudo docker info >/dev/null 2>&1; then
        sudo docker "$@"

        return
    fi

    echo "Docker is not available (tried docker and sudo docker)." >&2

    return 1
}

_cloud_docker_log() {
    if declare -F log >/dev/null 2>&1; then
        log "$@"
    else
        echo ">>> $*"
    fi
}

_cloud_docker_ok() {
    if declare -F ok >/dev/null 2>&1; then
        ok "$@"
    fi
}

_cloud_apt_dpkg_opts=(
    -o Dpkg::Options::=--force-confdef
    -o Dpkg::Options::=--force-confold
)

_cloud_apt_export_noninteractive() {
    export DEBIAN_FRONTEND=noninteractive
    export DEBCONF_NONINTERACTIVE_SEEN=true
}

# Cursor base images ship /etc/fuse.conf; fuse3's postinst prompts for a
# conffile decision unless we force a default. Retry apt for mirror blips.
_apt_install_with_retry() {
    local attempt

    _cloud_apt_export_noninteractive

    for attempt in 1 2 3; do
        sudo apt-get update -qq

        if sudo apt-get install -y --no-install-recommends \
            "${_cloud_apt_dpkg_opts[@]}" \
            "$@"; then
            return 0
        fi

        _cloud_docker_log "apt-get install attempt ${attempt} failed; retrying..."
        sleep 3
    done

    return 1
}

ensure_fuse_packages() {
    if ! dpkg -l fuse3 fuse-overlayfs 2>/dev/null | grep -q '^iU'; then
        return
    fi

    _cloud_docker_log "Repairing fuse package configuration..."

    _cloud_apt_export_noninteractive
    sudo apt-get install -y --no-install-recommends \
        "${_cloud_apt_dpkg_opts[@]}" \
        fuse3 fuse-overlayfs || true
    sudo dpkg --configure -a || true
}

ensure_docker_iptables_legacy() {
    # Nested Cursor Cloud pods often have a legacy FORWARD DROP policy while
    # docker.io defaults to nft. Mixed backends break container-to-container
    # traffic (MySQL connection timeouts from the app container).
    if [[ -x /usr/sbin/iptables-legacy ]]; then
        sudo update-alternatives --set iptables /usr/sbin/iptables-legacy >/dev/null 2>&1 || true
    fi

    if [[ -x /usr/sbin/ip6tables-legacy ]]; then
        sudo update-alternatives --set ip6tables /usr/sbin/ip6tables-legacy >/dev/null 2>&1 || true
    fi
}

ensure_docker_daemon_config() {
    # Nested pods sit on overlay; overlay2-in-overlay fails.
    # fuse-overlayfs is also what ./cc uses to select compose.cloud.yaml.
    local desired='{"storage-driver":"fuse-overlayfs"}'
    local current=''

    if [[ -f /etc/docker/daemon.json ]]; then
        current="$(tr -d '[:space:]' </etc/docker/daemon.json 2>/dev/null || true)"
    fi

    if [[ "$current" == *'"storage-driver":"fuse-overlayfs"'* ]]; then
        return
    fi

    sudo mkdir -p /etc/docker
    printf '%s\n' "$desired" | sudo tee /etc/docker/daemon.json >/dev/null
}

ensure_docker_installed() {
    ensure_fuse_packages

    if command -v docker >/dev/null 2>&1 && command -v dockerd >/dev/null 2>&1; then
        ensure_docker_daemon_config
        ensure_docker_iptables_legacy

        return
    fi

    _cloud_docker_log "Installing Docker (docker.io + compose plugin)..."

    if ! _apt_install_with_retry \
        docker.io \
        docker-compose-v2 \
        fuse-overlayfs; then
        echo "Failed to install Docker packages after 3 attempts." >&2
        exit 1
    fi

    ensure_docker_daemon_config
    ensure_docker_iptables_legacy

    if getent group docker >/dev/null 2>&1; then
        sudo usermod -aG docker "$(id -un)" 2>/dev/null || true
    fi

    if ! command -v docker >/dev/null 2>&1 || ! command -v dockerd >/dev/null 2>&1; then
        echo "Docker packages installed but docker/dockerd still not on PATH." >&2
        exit 1
    fi

    _cloud_docker_ok "Docker packages installed"
}

ensure_docker() {
    ensure_docker_installed

    if docker_cmd info >/dev/null 2>&1; then
        _cloud_docker_ok "Docker daemon is running"

        return
    fi

    _cloud_docker_log "Starting Docker daemon..."

    ensure_docker_daemon_config
    ensure_docker_iptables_legacy

    if ! pgrep -x dockerd >/dev/null 2>&1; then
        sudo dockerd >/tmp/dockerd.log 2>&1 &
    fi

    for _ in $(seq 1 60); do
        if [[ -S /var/run/docker.sock ]] && [[ ! -w /var/run/docker.sock ]]; then
            sudo chmod 666 /var/run/docker.sock 2>/dev/null || true
        fi

        if [[ -d /var/run ]] && [[ ! -x /var/run ]]; then
            sudo chmod 755 /var/run 2>/dev/null || true
        fi

        if docker_cmd info >/dev/null 2>&1; then
            break
        fi
        sleep 1
    done

    if [[ -S /var/run/docker.sock ]] && [[ ! -w /var/run/docker.sock ]]; then
        sudo chmod 666 /var/run/docker.sock 2>/dev/null || true
    fi

    if [[ -d /var/run ]] && [[ ! -x /var/run ]]; then
        sudo chmod 755 /var/run 2>/dev/null || true
    fi

    if ! docker_cmd info >/dev/null 2>&1; then
        if declare -F log >/dev/null 2>&1; then
            echo -e "${RED:-}Docker failed to start. See /tmp/dockerd.log${NC:-}" >&2
        else
            echo "Docker failed to start. See /tmp/dockerd.log" >&2
        fi
        exit 1
    fi

    _cloud_docker_ok "Docker daemon started"
}
