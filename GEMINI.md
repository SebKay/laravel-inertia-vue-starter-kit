<laravel-boost-guidelines>
=== .ai/planning rules ===

When making plans, always use the following skills: /brainstorming and /planner.

You MUST make atomic commits for each task completed in a plan. Make sure the plan reflects that.

=== .ai/project rules ===

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
- **Auth-only (not necessarily verified):** email verification notice, signed verify link, resend (`account/verify*`); **`GET|POST account/password`** for `password.confirm` (`ConfirmPasswordController` → Inertia `Password/Show`, `ConfirmPasswordStoreRequest`; POST `throttle:6,1`).
- **Super-admin:** `GET elements` → Inertia `Elements` (middleware `auth`, `password.confirm`, `role:super-admin`).

## Controllers and validation

- Keep controllers thin; use `app/Http/Requests/**` (auth, account, and password controllers already do).

## Auth behavior (side effects)

- **`RegisterController`:** new `User`, hashed password, `assignRole(Role::USER)`, `loginUsingId`, `Filament\Auth\Events\Registered`, redirect `route('home')`. Local/testing: fake prefills on the register form.
- **`LoginController`:** session guard, `remember`, optional `redirect` query, session regeneration on success; local/testing prefills from config/fake data.
- **`ConfirmPasswordController`:** `store` uses `current_password` validation; sets session `auth.password_confirmed_at`; `redirect()->intended(route('home'))`.

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

=== .ai/tailwind rules ===

# Tailwind in this app

Tailwind **v4** is wired through Vite (`@tailwindcss/vite` in `vite.config.js`), not a `tailwind.config.js` file. The entry point is `resources/css/app.css`.

## CSS-first pipeline

- **`@import "tailwindcss"`** — core.
- **`@plugin "@tailwindcss/forms"`** — form control resets/defaults (see package `@tailwindcss/forms`).
- **`@source "../../resources/";`** — content detection for class names under `resources/` (Vue, Blade, etc.). Filament has its own entry and sources (below).

## Design tokens (`resources/css/variables.css`)

Tokens live in an **`@theme { ... }`** block. They extend or replace Tailwind’s theme:

| Token                                           | Role                                                                                                                                                    |
| ----------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `--default-transition-duration`                 | `0.2s` (global transition duration)                                                                                                                     |
| `--font-text`, `--font-heading`, `--font-serif` | Body, heading, and serif stacks (currently Arial / Georgia placeholders). Exposed as utilities `font-text`, `font-heading`, `font-serif`.               |
| `--breakpoint-xs` … `--breakpoint-4xl`          | **Non-default breakpoints** (e.g. `sm` is `451px`, not Tailwind’s default `640px`). Prefer the app’s `sm:`, `md:`, etc. when matching existing layouts. |
| `--color-ui-1-1`                                | Primary brand green (`#257c53`). Use utilities like `bg-ui-1-1`, `text-ui-1-1`, `border-ui-1-1`, etc.                                                   |

Add new colors or fonts by defining `--color-*` / `--font-*` (and breakpoints if needed) in the same `@theme` block.

## Layer imports (after variables)

Order in `app.css`: `variables.css` → `typography.css` → `utilities.css` → `forms.css` → `buttons.css` → `tables.css` → `content.css`.

### Base layer

- `app.css`: `#app` gets `h-full`.
- `forms.css`: `label[for]` gets `cursor-pointer`.

### Custom `@utility` classes (use these instead of re-styling from scratch)

- **Links:** `text-link` — underline + hover behavior (`resources/css/utilities.css`).
- **Headings:** `heading`, `h1`–`h6` — responsive type scale + weight (`typography.css`).
- **Forms:** `form-row`, `form-col`, `form-col-2`, `label`, `small-label`, `inline-label`, `field`, `input`, `textarea`, `select`, `option`, `checkbox`, `radio`, `toggle`, `field-error`, `field-hint` (`forms.css`). Form pages should align with `resources/css/forms.css` and Blade `x-field-error` where applicable.
- **Buttons:** `button`, `button-full` — primary styling uses `bg-ui-1-1` (`buttons.css`).
- **Tables:** `table`, `table-wrap` (`tables.css`).
- **Rich HTML:** `content` — wraps headings, links (`text-link`), blockquotes (`font-serif`), lists, spacing, and nested `table` styling (`content.css`).

## Filament admin

`resources/css/filament/admin/theme.css` is a **separate Vite input**: it imports Filament’s vendor theme and adds `@source` for `app/Filament/**` and `resources/views/filament/**`. Inertia/app tokens from `app.css` are not automatically part of the Filament bundle unless duplicated or shared via that file.

## Tooling

- **Prettier:** `prettier-plugin-tailwindcss` sorts classes in `resources/`.
- **Vite aliases:** `@css` → `resources/css` (see `vite.config.js`).

## Quick reference for agents

1. Prefer **`bg-ui-1-1` / `text-ui-1-1`** (and related) for brand color, not arbitrary hex in new UI.
2. Remember **breakpoints differ from default Tailwind** — check `variables.css` before assuming standard `sm`/`md` widths.
3. Use **`font-text`** on the document body (`resources/views/app.blade.php`); headings in prose can use `heading` + `h1`–`h6` or the **`content`** wrapper for CMS-style HTML.

=== .ai/testing rules ===

## Testing Guidelines

- Don't add browser tests unless specifically asked to do so.
- Always use the /pest-testing skill to implement any Pest tests.
- Always use the /laravel-permission-development skill to implement any permission tests.

## Login Credentials

If you need to login as a user, use the following credentials:

- Super Admin:
  - Email: super@laravel-inertia-starter-kit.test
  - Password: 12345
- Admin:
  - Email: admin@laravel-inertia-starter-kit.test
  - Password: 12345
- User:
  - Email: user@laravel-inertia-starter-kit.test
  - Password: 12345

=== .ai/ui-implementation rules ===

If you have access, use the /ui skills and MCP from uidotsh to implement any UI Tailwind.

Always create reusable components where you can. If you're starting something new, see if a component for that already exists.

When adding forms, always use the form styles from @resources/css/forms.css.

When adding forms, always use the @resources/views/components/field-error.blade.php component to display validation errors.

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- filament/filament (FILAMENT) - v5
- inertiajs/inertia-laravel (INERTIA_LARAVEL) - v3
- laravel/framework (LARAVEL) - v13
- laravel/nightwatch (NIGHTWATCH) - v1
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/wayfinder (WAYFINDER) - v0
- livewire/livewire (LIVEWIRE) - v4
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- \@inertiajs/vue3 (INERTIA_VUE) - v3
- \@laravel/vite-plugin-wayfinder (WAYFINDER_VITE) - v0
- prettier (PRETTIER) - v3
- tailwindcss (TAILWINDCSS) - v4
- vue (VUE) - v3

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `laravel-best-practices` — Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns.
- `wayfinder-development` — Use this skill for Laravel Wayfinder which auto-generates typed functions for Laravel controllers and routes. ALWAYS use this skill when frontend code needs to call backend routes or controller actions. Trigger when: connecting any React/Vue/Svelte/Inertia frontend to Laravel controllers, routes, building end-to-end features with both frontend and backend, wiring up forms or links to backend endpoints, fixing route-related TypeScript errors, importing from @/actions or @/routes, or running wayfinder:generate. Use Wayfinder route functions instead of hardcoded URLs. Covers: wayfinder() vite plugin, .url()/.get()/.post()/.form(), query params, route model binding, tree-shaking. Do not use for backend-only task
- `pest-testing` — Use this skill for Pest PHP testing in Laravel projects only. Trigger whenever any test is being written, edited, fixed, or refactored — including fixing tests that broke after a code change, adding assertions, converting PHPUnit to Pest, adding datasets, and TDD workflows. Always activate when the user asks how to write something in Pest, mentions test files or directories (tests/Feature, tests/Unit, tests/Browser), or needs browser testing, smoke testing multiple pages for JS errors, or architecture tests. Covers: it()/expect() syntax, datasets, mocking, browser testing (visit/click/fill), smoke testing, arch(), Livewire component tests, RefreshDatabase, and all Pest 4 features. Do not use for factories, seeders, migrations, controllers, models, or non-test PHP code.
- `inertia-vue-development` — Develops Inertia.js v3 Vue client-side applications. Activates when creating Vue pages, forms, or navigation; using <Link>, <Form>, useForm, useHttp, setLayoutProps, or router; working with deferred props, prefetching, optimistic updates, instant visits, or polling; or when user mentions Vue with Inertia, Vue pages, Vue forms, or Vue navigation.
- `tailwindcss-development` — Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS.
- `configure-nightwatch` — Configures Laravel Nightwatch data collection, sampling rates, filtering rules, and redaction policies. Use when setting up Nightwatch, managing data volume, protecting sensitive data (PII), or optimizing event collection for production workloads.
- `laravel-permission-development` — Build and work with Spatie Laravel Permission features, including roles, permissions, middleware, policies, teams, and Blade directives.
- `debug-using-debugbar` — Use this skill to optimize requests or debug Laravel application issues — slow pages, N+1 queries, exceptions, failed requests, or unexpected behavior — by inspecting data captured by Laravel Debugbar via Artisan CLI commands. Use when the user asks to investigate a bug, diagnose a slow request, find duplicate queries, check what happened on a previous request, or optimize database performance, even if they don't explicitly mention "debugbar" or "profiling."

- `debugging-output-and-previewing-html-using-ray` — Use when user says "send to Ray," "show in Ray," "debug in Ray," "log to Ray," "display in Ray," or wants to visualize data, debug output, or show diagrams in the Ray desktop application.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `bun run build`, `bun run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd at `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs. Never run commands to serve the site. It is always available.
- Use the `herd` CLI to manage services, PHP versions, and sites (e.g. `herd sites`, `herd services:start <service>`, `herd php:list`). Run `herd list` to discover all available commands.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/Pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `bun run build` or ask the user to run `bun run dev` or `composer run dev`.

=== wayfinder/core rules ===

# Laravel Wayfinder

Use Wayfinder to generate TypeScript functions for Laravel routes. Import from `@/actions/` (controllers) or `@/routes/` (named routes).

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

=== filament/filament rules ===

## Filament

- Filament is used by this application. Follow the existing conventions for how and where it is implemented.
- Filament is a Server-Driven UI (SDUI) framework for Laravel that lets you define user interfaces in PHP using structured configuration objects. Built on Livewire, Alpine.js, and Tailwind CSS.
- Use the `search-docs` tool for official documentation on Artisan commands, code examples, testing, relationships, and idiomatic practices. If `search-docs` is unavailable, refer to https://filamentphp.com/docs.

### Artisan

- Always use Filament-specific Artisan commands to create files. Find available commands with the `list-artisan-commands` tool, or run `php artisan --help`.
- Always inspect required options before running a command, and always pass `--no-interaction`.

### Patterns

Always use static `make()` methods to initialize components. Most configuration methods accept a `Closure` for dynamic values.

Use `Get $get` to read other form field values for conditional logic:

<code-snippet name="Conditional form field visibility" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options(CompanyType::class)
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),

</code-snippet>

Use `state()` with a `Closure` to compute derived column values:

<code-snippet name="Computed table column value" lang="php">
use Filament\Tables\Columns\TextColumn;

TextColumn::make('full_name')
    ->state(fn (User $record): string => "{$record->first_name} {$record->last_name}"),

</code-snippet>

Actions encapsulate a button with an optional modal form and logic:

<code-snippet name="Action with modal form" lang="php">
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

Action::make('updateEmail')
    ->schema([
        TextInput::make('email')
            ->email()
            ->required(),
    ])
    ->action(fn (array $data, User $record) => $record->update($data))

</code-snippet>

### Testing

Always authenticate before testing panel functionality. Filament uses Livewire, so use `Livewire::test()` or `livewire()` (available when `pestphp/pest-plugin-livewire` is in `composer.json`):

<code-snippet name="Table test" lang="php">
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->assertCanSeeTableRecords($users)
    ->searchTable($users->first()->name)
    ->assertCanSeeTableRecords($users->take(1))
    ->assertCanNotSeeTableRecords($users->skip(1));

</code-snippet>

<code-snippet name="Create resource test" lang="php">
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

livewire(CreateUser::class)
    ->fillForm([
        'name' => 'Test',
        'email' => 'test@example.com',
    ])
    ->call('create')
    ->assertNotified()
    ->assertRedirect();

assertDatabaseHas(User::class, [
    'name' => 'Test',
    'email' => 'test@example.com',
]);

</code-snippet>

<code-snippet name="Testing validation" lang="php">
use function Pest\Livewire\livewire;

livewire(CreateUser::class)
    ->fillForm([
        'name' => null,
        'email' => 'invalid-email',
    ])
    ->call('create')
    ->assertHasFormErrors([
        'name' => 'required',
        'email' => 'email',
    ])
    ->assertNotNotified();

</code-snippet>

<code-snippet name="Calling actions in pages" lang="php">
use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

livewire(EditUser::class, ['record' => $user->id])
    ->callAction(DeleteAction::class)
    ->assertNotified()
    ->assertRedirect();

</code-snippet>

<code-snippet name="Calling actions in tables" lang="php">
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->callAction(TestAction::make('promote')->table($user), [
        'role' => 'admin',
    ])
    ->assertNotified();

</code-snippet>

### Correct Namespaces

- Form fields (`TextInput`, `Select`, etc.): `Filament\Forms\Components\`
- Infolist entries (`TextEntry`, `IconEntry`, etc.): `Filament\Infolists\Components\`
- Layout components (`Grid`, `Section`, `Fieldset`, `Tabs`, `Wizard`, etc.): `Filament\Schemas\Components\`
- Schema utilities (`Get`, `Set`, etc.): `Filament\Schemas\Components\Utilities\`
- Actions (`DeleteAction`, `CreateAction`, etc.): `Filament\Actions\`. Never use `Filament\Tables\Actions\`, `Filament\Forms\Actions\`, or any other sub-namespace for actions.
- Icons: `Filament\Support\Icons\Heroicon` enum (e.g., `Heroicon::PencilSquare`)

### Common Mistakes

- **Never assume public file visibility.** File visibility is `private` by default. Always use `->visibility('public')` when public access is needed.
- **Never assume full-width layout.** `Grid`, `Section`, and `Fieldset` do not span all columns by default. Explicitly set column spans when needed.

</laravel-boost-guidelines>
