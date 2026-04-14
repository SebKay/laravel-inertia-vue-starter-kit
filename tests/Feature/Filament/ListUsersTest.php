<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('users table does not include a status column', function () {
    actingAs(adminUser());

    Livewire::test(ListUsers::class)
        ->assertTableColumnDoesNotExist('status');
});
