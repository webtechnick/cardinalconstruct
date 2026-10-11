# AGENTS.md

## Product overview

Cardinal Construction (CC) — a Laravel 5.5 marketing site for Cardinal Construction
Inc (Albuquerque): public pages (home, about, contact/quote, partner pages), project
**galleries** with photo uploads, customer **reviews** (public submit + admin
moderation), and a small `/admin` back office for users and review approval. Client:
Ted Bennett. Stack: PHP 7.4 (Docker), MySQL 8, Redis, Blade + Laravel Elixir / Gulp 3
+ Vue 2. Hosted engagement; tickets and timers live on the WTN production MCP
(project code **CC**, `project_id: 6`).

Runs entirely in Docker via the **`./cc` wrapper** (php-fpm + nginx + MySQL + Redis;
Node 10 helper for assets). Default git branch is `master`.

## Always use `./cc`

**`./cc` is the single entry point for everything.** Do not call `php artisan`,
`composer`, `phpunit`, `npm`, `gulp`, or `docker compose` directly — route every
command through the wrapper so it runs in the right container against the right
services. Anything the wrapper doesn't special-case is forwarded into the `app`
container.

Run `./cc help` for the full, self-documenting command surface. Day-to-day:

```bash
# Container lifecycle (start Docker Desktop first)
./cc up                 # start all containers (bootstraps .env + APP_KEY on first run)
./cc down               # stop + remove
./cc restart            # restart all
./cc rebuild            # rebuild images after Dockerfile/compose changes
./cc ps                 # container status (alias: ./cc status)
./cc logs [service]     # follow logs (default: all)
./cc shell              # bash into the app container

# Laravel
./cc artisan <cmd>      # any artisan command
./cc tinker
./cc migrate            # php artisan migrate (args forwarded)
./cc fresh [--yes]      # migrate:fresh --seed (DESTRUCTIVE — confirmation unless --yes)
./cc seed
./cc cache              # clear app/config/route/view caches + Redis FLUSHALL

# Quality gates
./cc test [args]        # PHPUnit (SQLite :memory: testing connection)
./cc check              # local CI mirror: full PHPUnit suite

# Frontend (legacy elixir — node:10 tools profile)
./cc npm <cmd>
./cc dev                # gulp watch
./cc build              # gulp --production

# DB / cache CLIs (interactive — need a TTY)
./cc mysql              # MySQL client into the cardinalconstruct database
./cc redis              # redis-cli
```

> **Tests:** always run `./cc test` (or `./cc check`). PHPUnit uses the `testing`
> SQLite in-memory connection — never point a raw host `phpunit` at the Docker MySQL.

> **Interactive CLIs hang without a TTY:** `./cc mysql`, `./cc redis`, and
> `./cc tinker` open interactive sessions. Prefer `./cc artisan` one-offs in
> automation.

### Deploy (live — be careful)

There is **no in-repo deploy command or CI deploy workflow** yet. Production ships
are **manual** and require the user's explicit go-ahead. Confirm the host/path with
the user before any production change. Agents: see the **Ship / deploy** section in
[`.cursor/rules/wtn-production-mcp.mdc`](./.cursor/rules/wtn-production-mcp.mdc).

## Cursor rules (agent coding standards)

When working in Cursor, additional rules live in `.cursor/rules/`:

| Rule | When it applies |
|------|-----------------|
| [`project.mdc`](./.cursor/rules/project.mdc) | Always — stack, wrapper, Laravel 5.5 conventions |
| [`wtn-production-mcp.mdc`](./.cursor/rules/wtn-production-mcp.mdc) | Always — prod tickets, timers, MCP routing (not Atlassian) |

Detailed coding conventions are in `project.mdc`; this file is the ops and
orientation reference. Decision log: [`docs/decisions/`](./docs/decisions/).

## Repo map

| Path | Contents |
|------|----------|
| `app/` | Eloquent models at the `App\` root (`Gallery`, `Photo`, `Review`, …), plus `Http/`, `Traits/`, helpers. |
| `app/Http/Controllers/` | Public controllers + `Admin/` (users, review moderation) + `Auth/`. |
| `routes/web.php` | Classical string `@method` routes (Laravel 5.5). |
| `resources/views/` | Blade templates for public site + admin. |
| `resources/assets/` | Sass / JS sources for the Elixir pipeline. |
| `database/migrations/` | Users, posts/comments, galleries/photos, reviews. |
| `docker/` | PHP, nginx, MySQL config bind-mounted into Compose. |
| `tests/` | PHPUnit Feature + Unit suites. |
| `cc` | Docker-first wrapper CLI (this repo's `./WRAPPER`). |
| `docs/decisions/` | Architecture Decision Records. |

## CI / quality bars

No GitHub Actions workflow is checked in. The local quality bar is:

```bash
./cc check    # PHPUnit
```

Run it before pushing. There is no Pint/Pest on this stack — do not add them unless
asked.

## Local notes

- App URL: `http://localhost` (nginx on port 80). On Cursor Cloud:
  `http://cardinalconstruct.test`.
- Compose DB credentials: database/user `cardinalconstruct`, password `password`
  (see `docker-compose.yml`). `.env` must use `DB_HOST=db` / `REDIS_HOST=redis`.
- Asset builds need the `tools` profile (`./cc build` / `./cc npm`); they are not
  part of a normal `./cc up`. Committed `public/css` + `public/js` cover first paint.

## Cursor Cloud

On Cursor Cloud desktops **`./cc` auto-detects fuse-overlayfs** and applies
`compose.cloud.yaml` so MySQL data bind-mounts to `.data/mysql` (named volumes
break InnoDB on fuse-overlayfs).

```bash
./cc up              # cloud-prepare → compose up → post-up (key, fresh --yes, assets)
./cc check           # PHPUnit (starts the stack first if needed)
curl -I http://cardinalconstruct.test/
```

`.cursor/environment.json` runs `scripts/cloud-install.sh` (Docker + Composer)
and `scripts/cloud-start.sh` (hosts entry + `./cc up`) on agent boot. After you
verify once, **save an environment snapshot** from the
[Cloud Agents dashboard](https://cursor.com/dashboard/cloud-agents#environments)
and paste the snapshot id into `.cursor/environment.json`.

Secrets added in the Cursor Cloud **Secrets panel** arrive as host env vars.
`scripts/cloud-prepare.sh` mirrors an allowlist (`CLOUD_SECRET_KEYS` — Mailgun,
Stripe, Google, SES, mail credentials) into the bind-mounted `.env`. Add any new
integration key to that list or it will not reach the containers. Do **not**
paste secret values into git.
