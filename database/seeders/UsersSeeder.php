<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run()
    {
        User::factory()->superAdmin()->create();
        User::factory()->admin()->create();
        User::factory()->user()->create();
    }
}
