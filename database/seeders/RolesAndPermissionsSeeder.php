<?php

namespace Database\Seeders;

use App\Services\RolesAndPermissionsService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(RolesAndPermissionsService $service): void
    {
        $service->sync();
    }
}
