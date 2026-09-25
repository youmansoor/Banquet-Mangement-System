# Architecture

## Confirmed stack
- Laravel 12 application using PHP 8.2+
- Vite 7 frontend bundling
- Tailwind CSS 4
- Spatie Laravel Permission 6
- Pest 3 and Laravel test tooling

## Repository areas
- `app/` — application code
- `bootstrap/` — Laravel bootstrap and configuration
- `config/` — framework and application configuration
- `database/` — migrations, factories, and seeders
- `resources/` — views and frontend source
- `routes/` — application routes
- `tests/` — automated tests
- `public/` — public assets

## Entry points and tooling
- `artisan` — Laravel CLI
- `composer.json` — PHP dependencies and setup/test scripts
- `package.json` — Vite frontend scripts
- `AGENTS.md` — project-specific development rules
- `USER_RIGHTS.md` — existing user-rights documentation

## Risks and unknowns
- Active business modules and current feature priorities need owner confirmation.
- Database engine and local environment are not established from repository metadata alone.
- The repository contains committed assets under `public/assets/node_modules`; avoid modifying them unless explicitly required.
- Runtime verification must be performed in a configured local environment.
