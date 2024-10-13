<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Enums;

enum Attribute: string
{
    case UNDEFINED = 'undefined';

    public static function isUndefined(mixed $value): bool
    {
        return match ($value) {
            self::UNDEFINED => true,
            default => false,
        };
    }
}
