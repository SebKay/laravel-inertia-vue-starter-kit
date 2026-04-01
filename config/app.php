<?php

use App\Enums\Environment;

return [
    'env' => env('APP_ENV', Environment::PRODUCTION->value),
];
