<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Mattbit\Larepo\DTO\ModelDTOInterface;
use Mattbit\Larepo\Models\EloquentModelInterface;

abstract class EloquentRepository implements EloquentRepositoryInterface
{
    public function query(): Builder
    {
        return ($this->model())->newQuery();
    }

    public function all(): Collection
    {
        return $this->query()->get();
    }

    public function find(mixed $value, ?string $attribute = null): ?EloquentModelInterface
    {
        return $this->query()
            ->when(is_null($attribute), fn(Builder $query) => $query->find($value))
            ->when(!is_null($attribute), fn(Builder $query) => $query->where($attribute, $value)->first());
    }

    public function findOrFail(mixed $value, ?string $attribute = null): EloquentModelInterface
    {
        $model = $this->find($value, $attribute);
        if (is_null($model)) {
            throw new ModelNotFoundException(get_class($this->model()));
        }
        return $model;
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
