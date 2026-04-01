# Project Guidance

- This starter kit is centered on session auth, account management, and a Filament admin for `User`; `app/Models/User.php` is the only first-party model.
- `routes/web.php` is the main product surface: guest register/login, password reset, verified dashboard `/`, verified `/account`, email verification under `/account/verify`, a super-admin-only `elements` page, and Spatie Health results behind `auth` + `role:super-admin`.
- `bootstrap/app.php` is a high-impact file: it aliases Spatie permission middleware, appends `App\Http\Middleware\HandleInertiaRequests` to `web`, and renders Inertia `ErrorPage` for 403/404/419/500/503 outside `testing`.
- Keep controllers thin like `app/Http/Controllers/*Controller.php` and move validation into matching `app/Http/Requests/**` classes; `RegisterController` always assigns `Role::USER`, logs the user in, and dispatches `Filament\Auth\Events\Registered`.
- `app/Http/Controllers/DashboardController.php` uses Inertia v3 patterns: closure props, `Inertia::defer()` for `dashboard.stats`, and `Inertia::optional()` for super-admin-only data.
- Inertia shared auth state comes only from `app/Http/Middleware/HandleInertiaRequests.php` as `auth.user` via `app/Http/Resources/UserResource.php`; do not invent extra auth props.
- `routes/api.php` is intentionally tiny and returns the authenticated user model directly from `GET /user` with `auth:sanctum`, not `UserResource`.
- Frontend bootstraps in `resources/js/app.ts` with the default `resources/js/Layouts/App.vue`, global `viewTransition` visits, and page-level layout props; `resources/js/Pages/ErrorPage.vue` uses `resources/js/Layouts/Bare.vue`.
- `vite.config.js` enables Wayfinder with `formVariants: true`; prefer generated actions from `resources/js/actions/**` via the `@js/...` aliases instead of hardcoded URLs.
- Frontend permissions come from `auth.user.can`; `resources/js/utilities/permissions.ts` exists, but current pages also check the `can` array inline.
- `App\Enums\Role`, `App\Enums\Permission`, and `app/Services/RolesAndPermissionsService.php` are the authorization source of truth; when roles or permissions change, update the enums and sync service together.
- Seed order matters: `database/seeders/DatabaseSeeder.php` and `TestsSeeder.php` run `RolesAndPermissionsSeeder` before `UsersSeeder`; `config/seed.php` defines super/admin/user credentials, but `database/seeders/UsersSeeder.php` only creates super-admin and admin.
- Filament lives at `/admin` from `app/Providers/Filament/AdminPanelProvider.php`; it discovers resources/pages/widgets automatically, ships a custom login page, and only `super-admin` or `admin` may access the panel via `User::canAccessPanel()`.
- `app/Providers/AppServiceProvider.php` contains important global behavior: HTTPS forcing in staging/production, automatic eager loading, aggressive Vite prefetching, stricter production password rules, Filament table defaults, and Spatie Health check registration.
- Background behavior is minimal: `routes/console.php` schedules `RunHealthChecksCommand` every minute only in production; there are no first-party jobs, listeners, notifications, mailables, observers, policies, or broadcast channels in the repo today.
- Tests live in `tests/Feature`, `tests/Integration`, and `tests/Architecture`; feature tests seed `TestsSeeder` in `tests/Pest.php`, while `tests/TestCase.php` disables Inertia SSR and prevents stray HTTP requests.
