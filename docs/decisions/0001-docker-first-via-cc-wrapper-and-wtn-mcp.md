# 0001. Docker-first via `./cc`; ticket work on the WTN production MCP

- **Status:** Accepted
- **Date:** 2026-10-11
- **Deciders:** Nick Baker

## Context

Cardinal Construction is a long-lived Laravel 5.5 marketing site. Host PHP has
moved to 8.x while the locked Composer platform still requires **PHP 7.4**, and
the frontend still depends on the **Laravel Elixir / Gulp 3 / Node 10** toolchain.
Without a containerized entry point, contributors and agents reach for host
binaries, hit platform mismatches, or point tests at the wrong database.

Separately, freelance work for this client (Ted Bennett) is tracked on the
WebTechNick production MCP as project **CC** (`project_id: 6`), not in Jira.
Without repo-local rules, agents default to Atlassian or invent ticket IDs.

There is no GitHub Actions deploy workflow in this repo today; production updates
are a manual step after push.

## Decision

1. **All local development goes through the `./cc` wrapper** and Docker Compose
   (`app` / `webserver` / `db` / `redis`, plus the `node` tools profile for
   assets). Agents and humans do not invoke `php artisan`, `composer`, `phpunit`,
   `npm`, or `docker compose` directly.
2. **Ticket, timer, and ship-comment work** for this repo uses the
   `user-wtn-production` MCP against project CC only — never Atlassian.
3. **Deploy mode is `manual`.** Pushing to `master` does not deploy. Any
   production change needs the user's explicit go-ahead and a confirmed host
   path until an in-repo deploy surface is added.

Operational detail lives in [`AGENTS.md`](../../AGENTS.md) and
[`.cursor/rules/`](../../.cursor/rules/).

## Consequences

**Positive**

- PHP 7.4 / MySQL 8 / Redis parity between contributors; no host Composer
  platform fights.
- One discoverable command surface (`./cc help`) for agents.
- Ticket IDs and timers stay attached to the correct client project.

**Negative — explicitly accepted**

- Docker Desktop must be running for almost everything.
- Legacy Elixir/Node 10 asset builds stay on a separate compose profile.
- Manual deploy means agents must ask before shipping to production.

## Alternatives considered

**1. Upgrade Laravel / move to Vite before wiring SDLC.** Rejected for this pass:
the goal was operational binding (wrapper + MCP + decisions), not a framework
migration. Modernization can be a later ticket with its own ADR.

**2. Assume CI-on-main deploy.** Rejected: no workflow files exist in the repo,
so claiming `ci-on-main` would lie to `/end-ticket` and risk double-deploy
confusion later.
