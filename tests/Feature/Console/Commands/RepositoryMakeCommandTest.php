<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Mattbit\Larepo\Console\Commands\RepositoryMakeCommand;

it('can create repository', function (string $class) {
    $this
        ->artisan(RepositoryMakeCommand::class, ['name' => $class])
        ->assertSuccessful();

    $this->assertTrue(File::exists(app_path("Repositories/{$class}Repository.php")));
})->with([
    'User',
    'Product',
    'Cart',
]);
