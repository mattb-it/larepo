<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Models;

interface ModelInterface
{
    public function setAttribute($attribute, $value);
    public function getAttribute($attribute);
}
