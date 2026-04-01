<?php

use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;

test('missing pages render the inertia error page', function () {
    get('/this-page-does-not-exist')
        ->assertNotFound()
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('ErrorPage')
                ->where('status', 404)
        );
});
