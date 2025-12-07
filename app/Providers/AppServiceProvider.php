<?php

namespace App\Providers;

use App\Enums\Environment;
use Filament\Tables\Table;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
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
        URL::forceHttps(in_array(app()->environment(), [Environment::PRODUCTION, Environment::STAGING]));

        RequestException::dontTruncate();

        JsonResource::withoutWrapping();

        Vite::useAggressivePrefetching();

        Table::configureUsing(function (Table $table): void {
            $table->defaultPaginationPageOption(50);
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
