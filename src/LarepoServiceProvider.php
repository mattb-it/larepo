<?php

declare(strict_types=1);

namespace Mattbit\Larepo;

use Illuminate\Support\ServiceProvider;
use Mattbit\Larepo\Console\Commands\ModelDTOMakeCommand;
use Mattbit\Larepo\Console\Commands\RepositoryMakeCommand;

final class LarepoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    RepositoryMakeCommand::class,
                    ModelDTOMakeCommand::class,
                ],
            );
        }
    }
}
