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

# Code style
./vendor/bin/pint    # Laravel Pint formatter

# Testing
php artisan test                        # All tests
php artisan test --filter=TestName      # Single test class
```

**Default credentials (after seeding):** `admin@admin.com` / `Admin`

## Architecture

### Request Flow
`public/index.php` → `routes/web.php` (auth + public utils) + `routes/admin.php` (all protected pages) → Controllers or Livewire components → Blade views in `resources/views/`

All admin routes are in `routes/admin.php` wrapped in `["user_status", "set_school_year"]` middleware. Every route is further guarded by a `can:` permission gate.

### Two UI Layers
- **Controllers** (`app/Http/Controllers/`): Traditional MVC for page rendering, PDF/Excel exports, and receipt printing.
- **Livewire** (`app/Http/Livewire/`, ~50 components): Reactive UI for CRUD tables and forms. Each component renders by calling `->extends("adminlte::page")` in `render()`. Views live in `resources/views/livewire/`.

### SchoolYear Multi-Tenancy
`Summary` has a **global scope** (`App\Models\Scopes\SchoolYearScope`) that automatically filters all queries by the `current_school_year_id` stored in the session. The `SetSchoolYear` middleware seeds that session value from the active `SchoolYear` record on every request.

When querying `Summary` across all school years (e.g., for receipt numbering), always call:
```php
Summary::withoutGlobalScope(SchoolYearScope::class)->...
```

### Core Data Model
- **`Summary`** — a financial transaction (receipt). `type` is `'add'` (income) or `'out'` (expense). `status` is `'PAID'`, `'PENDING'`, or `'NULLED'`. `future=1` means the record is visible in the movements list. Receipt series/number are auto-assigned in the `Summary::boot()` creating/updating hooks based on the linked `Account`'s `add_serie`/`out_serie`.
- **`Detail`** — line items belonging to a `Summary`.
- **`Student`** → `Enrollment` → `SchoolYear` / `Grade` / `Section` — student enrollment hierarchy.
- **`Account`** — bank/cash account; holds `add_serie` and `out_serie` for receipt numbering.
- **`Setting`** — singleton company config read via `SystemService`.

### Livewire Filter Pattern
Components like `ListadoMovimiento` use a two-variable pattern to avoid re-querying on every keystroke:
- `$start1`, `$finish1`, etc. — **pending** values bound to form inputs.
- `$start`, `$finish`, etc. — **applied** values that drive `buildQuery()`.

Calling `filter()` copies pending → applied and resets pagination. Date ranges are restricted to a single month by `validarFechas()`.

### Key Modules
| Module | Controller | Livewire Namespace |
|---|---|---|
| Financial Accounts | `AccountController`, `BalanceController` | `Account/`, `Balances/` |
| Movements (Income/Expense) | `MovimientoController` | `Movimientos/` |
| Students ("Socios") | — | `Students/`, `Socios/` |
| Provisions | — | `Movimientos/Provisiones/` |
| Closures | — | `CierreMoviemientos/` |
| Reports & Exports | `ExportController`, `ReportController`, `ReporteIngresoGastoController` | `ReporteCC5/`, `Reportes/` |
| Users & Roles | — | `Usuarios/` |
| Audit Log | — | `Bitacora/` |
| System Settings | — | `Sistema/` |

### Services (`app/Services/`)
- `SystemService` — singleton for company-wide config (name, logo, RUC) from the `Setting` model.
- `ConsultaDniApi` / `ConsultaRucApi` — Peruvian DNI/RUC lookup integrations.
- `TipoCambioService` — currency exchange rates.
- `LimitDateService` — date range constraints.

### Permissions & Roles
Roles and permissions managed via **Spatie Permission**. Seeded by `RoleSeeder` and `PermisoSeeder`. Roles in descending privilege order: `SUPERADMINISTRADOR`, `ADMINISTRADOR`, `CONTADOR`, `CAJERO`, `ANALISTA`, `OPERADOR`, `USUARIO`, `AUDITOR`.

### Exports & Reports
15+ Maatwebsite Excel export classes in `app/Exports/`. PDF reports use Laravel DomPDF. Charts use Larapex Charts (`app/Charts/`). Receipt PDFs have three formats: ticket, A4, A5.

## Frontend
- AdminLTE 3 admin template (configured in `config/adminlte.php`)
- Bootstrap 5, SASS in `resources/sass/`
- Vite bundles JS/CSS; entry points in `vite.config.js`

## Utility Routes (web.php)
`/migrate`, `/storage_link`, `/cache-clear`, `/config-clear` are exposed **without authentication** in `routes/web.php`. These are convenience endpoints for production deploys but should be removed or protected in hardened environments.
