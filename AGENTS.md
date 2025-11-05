# AGENTS — Operating Rules for Codex

## Goals
Implement role-based order status logic with admin UI and tests. Never commit secrets.

## Branching
- One small feature per PR. Branch name: `feat/<slug>` or `fix/<slug>`.

## Commands
- PHP deps: `composer install`
- Lint: `vendor/bin/phpcs --standard=phpcs.xml`  
- Static: `vendor/bin/phpstan analyse`
- Unit (if added): `vendor/bin/phpunit`

## Acceptance checks for each PR
- No phpcs or phpstan errors.
- Minimum WordPress/WooCommerce versions unchanged.
- Unit tests added when changing business logic.

## Conventions
- Prefix functions/classes with `WROS_`.
- Escape, sanitize, validate admin inputs. Use nonces/capabilities.
- Do not alter paid orders (`processing/completed`).
