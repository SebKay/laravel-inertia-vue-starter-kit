<?php

use App\Console\Commands\PruneUnverifiedUsersCommand;
use App\Enums\Environment;
use Illuminate\Support\Facades\Schedule;
use Spatie\Health\Commands\RunHealthChecksCommand;

Schedule::command(PruneUnverifiedUsersCommand::class)
    ->dailyAt('03:00')
    ->timezone('UTC')
    ->withoutOverlapping()
    ->environments(Environment::PRODUCTION->value);

Schedule::command(RunHealthChecksCommand::class)->everyMinute()->withoutOverlapping()->environments(Environment::PRODUCTION->value);
