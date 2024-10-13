<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ModelDTOMakeCommand extends GeneratorCommand
{
    protected $signature = 'larepo:make:modeldto {name : The name of the Eloquent model}';
    protected $description = 'Create a new model DTO class';
    protected $type = 'DTO';

    protected function getPath($name)
    {
        return parent::getPath($name . 'DTO');
    }

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/model_dto.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\DTO\\Models";
    }
}
