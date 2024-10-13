<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Repositories;

use Illuminate\Support\Collection;
use Mattbit\Larepo\DTO\ModelDTOInterface;
use Mattbit\Larepo\Models\EloquentModelInterface;

abstract class EloquentRepository implements EloquentRepositoryInterface
{
    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function find(mixed $id): ?EloquentModelInterface
    {
        return $this->query()->find($id);
    }

    public function save(ModelDTOInterface $dto, EloquentModelInterface $model): EloquentModelInterface
    {
        foreach (get_object_vars($dto) as $attribute => $value) {
            $model->setAttribute($attribute, $value);
        }
        $model->save();
        return $model;
    }

    public function delete(EloquentModelInterface $model): bool
    {
        return (bool)$model->delete();
    }
}
