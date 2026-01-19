<?php

namespace App\Providers;

use App\Enums\Environment;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        URL::forceHttps(app()->environment([Environment::PRODUCTION->value, Environment::STAGING->value]));

        Model::automaticallyEagerLoadRelationships();

        RequestException::dontTruncate();

        JsonResource::withoutWrapping();

        Vite::useAggressivePrefetching();

        Password::defaults(function () {
            $rule = Password::min(6);

            return $this->app->environment([Environment::PRODUCTION->value, Environment::STAGING->value])
                ? $rule->mixedCase()->numbers()->symbols()->uncompromised()
                : $rule;
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->defaultPaginationPageOption(50)
                ->paginationPageOptions([5, 10, 25, 50, 'all']);
        });

        Health::checks([
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(50)
                ->failWhenMoreConnectionsThan(100),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
            SecurityAdvisoriesCheck::new(),
        ]);
    }
}
