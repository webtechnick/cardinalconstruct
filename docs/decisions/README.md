# Architecture Decision Records (ADRs)

This folder captures durable architectural and policy decisions for Cardinal
Construction (CC). Anything affecting how the system is structured, how data is
modeled, or how the dev/deploy workflow operates belongs here. The goal is
twofold:

1. **Future-proof context** — six months from now, when someone asks "why Docker
   + PHP 7.4?" or "why tickets on the WTN MCP?", the answer lives in one
   searchable place.
2. **AI agent onboarding** — new chat sessions can read this folder up front to
   inherit the same constraints (see [`AGENTS.md`](../../AGENTS.md)).

## Index

| # | Title | Status |
|---|-------|--------|
| [0001](./0001-docker-first-via-cc-wrapper-and-wtn-mcp.md) | Docker-first via `./cc`; ticket work on the WTN production MCP | Accepted |

## When to add an ADR

Open one whenever you make a decision that:

- Encodes a policy (e.g. "all commands go through `./cc`").
- Has a non-obvious trade-off worth recording.
- Will surprise a future contributor if they don't have the context.
- Cuts across more than one layer (backend ↔ frontend ↔ infra).

You do **not** need an ADR for routine bug fixes, refactors, formatting, or
decisions obviously implied by the framework.

## Format

Numbered, kebab-cased, descriptive title. Use `0001-`, `0002-`, etc. — pad to
four digits for stable sort. Each ADR follows the same skeleton:

```markdown
# NNNN. Title

- **Status:** Accepted | Superseded by NNNN | Deprecated
- **Date:** YYYY-MM-DD
- **Deciders:** Names / roles

## Context

What forced the decision. Symptoms observed, constraints, prior approaches
considered.

## Decision

The actual call we're making, stated unambiguously.

## Consequences

What changes, what breaks, what to watch for. Include both positive and
negative.

## Alternatives considered

Other paths and why they lost.
```

If a later decision overrides an earlier one, mark the older ADR
`Superseded by NNNN` rather than deleting it — the history is the point. Update
the index table above whenever you add or supersede an ADR.
