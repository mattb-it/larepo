<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Mattbit\Larepo\Console\Commands\ModelDTOMakeCommand;

it('can create model DTO', function (string $class) {
    $this
        ->artisan(ModelDTOMakeCommand::class, ['name' => $class])
        ->assertSuccessful();

    $this->assertTrue(File::exists(app_path("DTO/Models/{$class}DTO.php")));
})->with([
    'User',
    'Product',
    'Cart',
]);
