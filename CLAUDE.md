# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Caja Calasanz** is a Laravel 10 + Livewire 2 financial management system for an educational institution. It manages student billing, bank accounts, income/expense movements, reports, and user permissions.

## Common Commands

```bash
# Development
npm run dev          # Vite dev server with hot reload
php artisan serve    # Laravel dev server

# Production build
npm run build

# Database
php artisan migrate
php artisan db:seed

# Testing
php artisan test                        # All tests
php artisan test --filter=TestName      # Single test class
```

**Default credentials (after seeding):** `admin@admin.com` / `Admin`

## Architecture

### Request Flow
`public/index.php` → `routes/web.php` + `routes/admin.php` → Controllers or Livewire components → Blade views in `resources/views/`

Admin routes are in `routes/admin.php` (protected by auth middleware). The dashboard is `App\Http\Controllers\Admin\HomeController::dashboard()`.

### Two UI Layers
- **Controllers** (`app/Http/Controllers/`): Traditional MVC for pages, exports, PDF generation
- **Livewire** (`app/Http/Livewire/`, 66 components): Reactive UI for CRUD operations, forms, and tables. Corresponding views are in `resources/views/livewire/`

### Key Modules
| Module | Controller | Livewire Namespace |
|---|---|---|
| Financial Accounts | `AccountController`, `BalanceController` | `Account/`, `Balances/` |
| Movements (Income/Expense) | `MovimientoController` | `Movimientos/` |
| Students | — | `Students/` |
| Reports & Exports | `ExportController`, `ReporteIngresoGastoController` | — |
| Users & Roles | — | `Usuarios/` |
| Audit Log | — | `Bitacora/` |
| System Settings | — | `Sistema/` |

### Data Model (Core Entities)
- `Summary` — financial receipts/records; belongs to `User`, `Student`, `Account`, `Category`
- `Detail` — line items within a summary
- `Student` → `Enrollment` → `SchoolYear`/`Grade`/`Section`
- `User` uses Spatie `HasRoles` for RBAC

### Services (`app/Services/`)
- `SystemService` — singleton for company-wide config
- `ConsultaDniApi` / `ConsultaRucApi` — external API integrations for Peruvian ID lookup
- `TipoCambioService` — currency exchange rates
- `LimitDateService` — date range constraints

### Exports & Reports (`app/Exports/`)
15+ Maatwebsite Excel export classes. PDF reports use Laravel DomPDF. Charts use Larapex Charts (`app/Charts/`).

### Permissions
Roles and permissions managed via Spatie Permission. Seeded by `database/seeders/RoleSeeder.php` and `PermisoSeeder.php`.

## Frontend
- AdminLTE 3 admin template (configured in `config/adminlte.php`)
- Bootstrap 5, SASS in `resources/sass/`
- Vite bundles JS/CSS; entry points in `vite.config.js`
