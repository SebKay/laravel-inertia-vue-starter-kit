# Project Guidance

## Domain and data

- Session-first app: guest/auth flows, verified Inertia area, Filament admin. **Sole first-party Eloquent model:** `app/Models/User.php` (`MustVerifyEmail`, Sanctum `HasApiTokens`, Spatie `HasRoles`, Filament `FilamentUser` / `HasName`).
- `User::canAccessPanel()` allows only `super-admin` and `admin` (`app/Enums/Role.php`).

## HTTP surface

- **Routes:** `routes/web.php` only; no first-party `routes/api.php`.
- **Laravel health:** `GET /up` from `bootstrap/app.php` `withRouting(health: ...)`.
- **Spatie Health UI:** `GET /health`, middleware `auth` + `role:super-admin` (`Spatie\Health\Http\Controllers\HealthCheckResultsController`).
- **Guest:** register, login, forgot/reset password (see controller imports in `routes/web.php`). POST actions for register, login, password, and verification resend use `throttle:6,1` where declared on the route.
- **Auth:** `POST logout` (`auth`).
- **Verified (`auth` + `verified`):** `GET /` dashboard, `GET|PATCH /account`.
- **Auth-only (not necessarily verified):** email verification notice, signed verify link, resend (`account/*`).
- **Super-admin:** `GET elements` → Inertia `Elements`.

## Controllers and validation

- Keep controllers thin; use `app/Http/Requests/**` (auth, account, and password controllers already do).

## Auth behavior (side effects)

- **`RegisterController`:** new `User`, hashed password, `assignRole(Role::USER)`, `loginUsingId`, `Filament\Auth\Events\Registered`, redirect `route('home')`. Local/testing: fake prefills on the register form.
- **`LoginController`:** session guard, `remember`, optional `redirect` query, session regeneration on success; local/testing prefills from config/fake data.

## Dashboard and Inertia

- **`DashboardController`:** returns `inertia('Dashboard/Index')` with **no server props**; the Vue page is currently a static placeholder. Use Inertia v3 deferred/optional props in the controller if you add stats or role-gated fragments.
- **Shared auth:** `app/Http/Middleware/HandleInertiaRequests.php` shares `auth.user` via `UserResource` (`app/Http/Resources/UserResource.php`, JSON:API resource; includes permission names as `can`). Prefer `auth.user` / `can` on the client for gates.
- **Account page:** `AccountController@edit` also passes a top-level `user` resource for the form (`resources/js/Pages/Account/Edit.vue`).

## Frontend

- **Bootstrap:** `resources/js/app.ts` — async `Layouts/App.vue`, global `Head` / `Link` / `PageTitle` / `Notice`, default `viewTransition: true`. Pages set chrome with `setLayoutProps()`.
- **Wayfinder:** `vite.config.js` (`formVariants: true`). Generated controller actions import from `@js/actions/...` (`tsconfig.json` `paths`).

## Bootstrap and global PHP

- **`bootstrap/app.php`:** Spatie middleware aliases (`role`, `permission`, `role_or_permission`), append `HandleInertiaRequests` to `web`, Inertia `ErrorPage` for **403, 404, 419, 500, 503**.
- **`app/Providers/AppServiceProvider.php`:** HTTPS in staging/production, model relationship auto-eager load, Vite prefetch behavior, production password rules, Filament table defaults, Spatie Health check registration — inspect before changing cross-cutting behavior.

## Roles and permissions

- **Source of truth:** `app/Enums/Role.php`, `app/Enums/Permission.php`, `app/Services/RolesAndPermissionsService.php` — keep enums and sync logic aligned.
- **CLI:** `php artisan permissions:sync` and `permissions:sync --fresh` (`app/Console/Commands/SyncRolesAndPermissionsCommand.php`).

## Seeders

- `DatabaseSeeder` and `TestsSeeder` run `RolesAndPermissionsSeeder` before `UsersSeeder`. `UsersSeeder` creates super-admin, admin, and a standard user via `User::factory()` states (`database/factories/UserFactory.php`). Credentials reference: `config/seed.php`.

## Filament

- Panel path `/admin`: `app/Providers/Filament/AdminPanelProvider.php`, custom login `app/Filament/Pages/Auth/Login.php`. User admin CRUD: `app/Filament/Resources/Users/**`. Dashboard widgets include `UserStats`, `UserActivityChart` under `app/Filament/Widgets/`.

## Background

- **`routes/console.php`:** `RunHealthChecksCommand` every minute **only** in `production` (`App\Enums\Environment::PRODUCTION`).
- No first-party jobs, listeners, notifications, mailables, policies, or broadcast channels under `app/` today.

## Tests

- Layout: `tests/Feature`, `tests/Integration`, `tests/Architecture`. Feature setup: `tests/Pest.php` uses `TestsSeeder`. `tests/TestCase.php` sets `inertia.ssr.enabled` false and `Http::preventStrayRequests()`.
