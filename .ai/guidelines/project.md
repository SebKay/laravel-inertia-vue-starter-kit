# Project Guidance

- This repo is a starter kit centered on session auth, account management, and a Filament admin for `User`; there are no other first-party domain models in `app/Models`.
- `routes/web.php` is the main product surface: guest auth flows, password reset, `/` dashboard, `/account`, email verification, a super-admin-only `elements` page, and Spatie Health results behind `auth` + `role:super-admin`.
- Keep controllers thin like the existing `app/Http/Controllers/*Controller.php` files and put request validation in the matching `app/Http/Requests/**` classes.
- Inertia shared auth state comes from `app/Http/Middleware/HandleInertiaRequests.php` as `auth.user` via `app/Http/Resources/UserResource.php`; frontend permission checks rely on `resources/js/utilities/permissions.ts`.
- Do not assume `auth.loggedIn` exists on the page props just because `resources/js/types/inertia.d.ts` declares it; the middleware currently only shares `auth.user`.
- Frontend navigation uses Inertia Vue in `resources/js/app.ts` with the default `resources/js/Layouts/App.vue`; prefer Wayfinder-generated actions from `@js/actions/...` because `vite.config.js` enables the Wayfinder plugin.
- `App\Enums\Role`, `App\Enums\Permission`, and `app/Services/RolesAndPermissionsService.php` are the source of truth for authorization; when roles or permissions change, update the enums and sync logic together.
- Registration in `app/Http/Controllers/RegisterController.php` always assigns `Role::USER`; Filament panel access in `app/Models/User.php` is limited to `super-admin` and `admin`.
- Seeders depend on the role sync order: `database/seeders/DatabaseSeeder.php` and `TestsSeeder.php` run `RolesAndPermissionsSeeder` before `UsersSeeder`.
- Default seeded credentials live in `config/seed.php`, and `database/factories/UserFactory.php` states (`superAdmin()`, `admin()`, `user()`) should stay aligned with that config.
- The Filament admin is mounted at `/admin` from `app/Providers/Filament/AdminPanelProvider.php`; the only resource today is `app/Filament/Resources/Users/UserResource.php`.
- Filament dashboard widgets in `app/Filament/Widgets` currently report total users and 30-day registrations, so changes to user creation should consider both counts and chart queries.
- `app/Providers/AppServiceProvider.php` contains important global behavior: HTTPS forcing in staging/production, automatic eager loading, aggressive Vite prefetching, stricter production password rules, Filament table defaults, and Spatie Health check registration.
- Scheduled/background behavior is intentionally minimal: `routes/console.php` runs Spatie Health checks every minute only in production, `.env.example` defaults `QUEUE_CONNECTION=sync`, and there are no app-owned jobs, listeners, notifications, mailables, observers, or broadcasting channels.
- Password reset and email verification use Laravel's built-in broker/notifications from `ResetPasswordController` and `EmailVerificationController`; there are no custom notification classes to extend first.
- The API surface is intentionally tiny: `routes/api.php` only exposes authenticated `GET /user` via Sanctum.
- Tests are concentrated in `tests/Feature/Controllers` and `tests/Integration/Services/RolesAndPermissionsServiceTest.php`; preserve those conventions when changing auth, account, or permission behavior.
