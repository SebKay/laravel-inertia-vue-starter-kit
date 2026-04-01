<?php

use App\Enums\Environment;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

test('missing pages render the inertia error page outside testing', function () {
    app()->instance('env', Environment::LOCAL->value);

    get('/this-page-does-not-exist')
        ->assertNotFound()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('ErrorPage')
                ->where('status', 404)
        );
});
