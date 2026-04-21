<?php

use Illuminate\Support\Facades\Process;

test('production build keeps the vue runtime chunk independent from the page title chunk', function () {
    $result = Process::path(base_path())
        ->timeout(300)
        ->run('bun run build');

    if (! $result->successful()) {
        $this->fail(trim($result->errorOutput().PHP_EOL.$result->output()));
    }

    $manifest = json_decode(
        file_get_contents(public_path('build/manifest.json')),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    $sharedChunkFiles = array_merge(
        [public_path('build/'.$manifest['resources/js/app.ts']['file'])],
        glob(public_path('build/assets/vue.runtime*.js')),
    );

    foreach ($sharedChunkFiles as $sharedChunkFile) {
        $sharedChunkContents = file_get_contents($sharedChunkFile);

        expect($sharedChunkContents)
            ->toBeString()
            ->not->toContain('PageTitle')
            ->not->toContain('from"./PageTitle-');
    }
});
