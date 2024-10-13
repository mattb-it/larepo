<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Models;

interface EloquentModelInterface
{
    public function save();
    public function delete();
}
