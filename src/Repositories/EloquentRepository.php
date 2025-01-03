<?php

declare(strict_types=1);

namespace Mattbit\Larepo\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mattbit\Larepo\DTO\ModelDTOInterface;
use Mattbit\Larepo\Enums\Attribute;

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

    public function find(mixed $value, ?string $attribute = null): ?Model
    {
        return $this->query()
            ->when(is_null($attribute), fn(Builder $query) => $query->whereKey($value))
            ->when(!is_null($attribute), fn(Builder $query) => $query->where($attribute, $value))
            ->first();
    }

    public function findOrFail(mixed $value, ?string $attribute = null): Model
    {
        $model = $this->find($value, $attribute);
        if (is_null($model)) {
            throw new ModelNotFoundException(get_class($this->model()));
        }
        return $model;
    }

    public function save(ModelDTOInterface $dto, ?Model $model = null): Model
    {
        $model = $model ?? $this->model();

        collect(get_object_vars($dto))
            // Filter out undefined attributes
            ->filter(fn(mixed $value, string $attribute) => Attribute::isDefined($value))
            // Map attribute names to snake case
            ->mapWithKeys(fn(mixed $value, string $attribute) => [Str::snake($attribute) => $value])
            // Set attributes on the model
            ->each(fn(mixed $value, string $attribute) => $model->setAttribute($attribute, $value));

        $model->save();
        return $model;
    }

    public function delete(Model $model): bool
    {
        return (bool)$model->delete();
    }
}
