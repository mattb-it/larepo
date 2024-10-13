<?php

declare(strict_types=1);

namespace Workbench\App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Mattbit\Larepo\Repositories\EloquentRepository;
use Workbench\App\Models\User;

final class UserRepository extends EloquentRepository
{
    public function query(): Builder
    {
        return User::query();
    }
}
