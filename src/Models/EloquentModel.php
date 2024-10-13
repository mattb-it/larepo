<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Models;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

abstract class EloquentModel extends Model implements EloquentModelInterface
{
    public function __get($key)
    {
        throw new RuntimeException('Use getAttribute method instead');
    }

    public function __set($key, $value)
    {
        throw new RuntimeException('Use setAttribute method instead');
    }
}
