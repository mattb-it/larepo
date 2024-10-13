<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class RepositoryMakeCommand extends GeneratorCommand
{
    protected $signature = 'larepo:make:repository {name : The name of the Eloquent model}';
    protected $description = 'Create a new repository class';
    protected $type = 'Repository';

    protected function getPath($name)
    {
        return parent::getPath($name . 'Repository');
    }

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\Repositories";
    }
}
