<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mattbit\Larepo\DTO\ModelDTOInterface;

interface EloquentRepositoryInterface
{
    public function model(): Model;
    public function query(): Builder;
    public function all(): Collection;
    public function find(mixed $id): ?Model;
    public function save(ModelDTOInterface $dto, Model $model): Model;
    public function delete(Model $model): bool;
}
