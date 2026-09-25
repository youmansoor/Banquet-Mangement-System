# Project Overview

## Name
Banquet Management System

## Purpose
A Laravel-based banquet management application. The exact business scope and active modules require confirmation from the project owner.

## Technology
- Backend: Laravel 12
- PHP: 8.2+
- Database: Laravel-configured database; confirm from `.env`/deployment configuration
- Frontend build: Vite 7 with Tailwind CSS 4
- Authorization: spatie/laravel-permission
- Testing: Pest 3 / Laravel test runner

## Main Modules
- Existing application modules: discovery in progress
- User rights and permissions: documented in `USER_RIGHTS.md`

## Current Status
- Discovery: Initial repository discovery completed
- Planning: Waiting for first feature request
- Development: Not started for current task
- Testing: Existing test suite requires local environment execution
- Delivery: Not started

## Important Constraints
- Follow the root `AGENTS.md` instructions.
- Do not change dependencies without approval.
- Review migrations, authentication/authorization, destructive operations, and financial logic before implementation.
- Use existing project conventions and verify commands locally.

## Known project commands
- `composer test`
- `php artisan test`
- `npm run build`
- `npm run dev`
