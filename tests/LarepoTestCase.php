<?php

namespace Mattbit\Larepo\Tests;

use Mattbit\Larepo\LarepoServiceProvider;

class LarepoTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LarepoServiceProvider::class,
        ];
    }
}
