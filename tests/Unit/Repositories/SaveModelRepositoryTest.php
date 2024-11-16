<?php

use Mattbit\Larepo\Enums\Attribute;

$userDTO = new class implements \Mattbit\Larepo\DTO\ModelDTOInterface {
    public function __construct(
        public Attribute|int $id = Attribute::UNDEFINED,
        public Attribute|string $name = Attribute::UNDEFINED,
    ) {
    }
};

it('can save', function () use ($userDTO) {
    $userRepository = new class extends \Mattbit\Larepo\Repositories\EloquentRepository {
        public function model(): \Illuminate\Database\Eloquent\Model
        {
            return new class extends \Illuminate\Database\Eloquent\Model {
                public function newQuery()
                {
                    return \Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                }
            };
        }
    };

    $userModelMock = \Mockery::spy(\Illuminate\Database\Eloquent\Model::class);

    $userRepository->save(
        new $userDTO(
            id: 10,
            name: 'John Doe',
        ),
        $userModelMock,
    );

    $userModelMock->shouldHaveReceived('setAttribute')->with('id', 10);
    $userModelMock->shouldHaveReceived('setAttribute')->with('name', 'John Doe');
    $userModelMock->shouldHaveReceived('save');
});

it('can save only defined attributes', function () use ($userDTO) {
    $userRepository = new class extends \Mattbit\Larepo\Repositories\EloquentRepository {
        public function model(): \Illuminate\Database\Eloquent\Model
        {
            return new class extends \Illuminate\Database\Eloquent\Model {
                public function newQuery()
                {
                    return \Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                }
            };
        }
    };

    $userModelMock = \Mockery::spy(\Illuminate\Database\Eloquent\Model::class);

    $userRepository->save(
        new $userDTO(
            name: 'John Doe',
        ),
        $userModelMock,
    );

    $userModelMock->shouldNotHaveReceived('setAttribute', ['id', 10]);
    $userModelMock->shouldHaveReceived('setAttribute')->with('name', 'John Doe');
    $userModelMock->shouldHaveReceived('save');
});

it('can create a new model', function () use ($userDTO) {
    $model = \Mockery::spy(\Illuminate\Database\Eloquent\Model::class);

    $userRepository = new class($model) extends \Mattbit\Larepo\Repositories\EloquentRepository {

        public function __construct(private \Illuminate\Database\Eloquent\Model $model) {}

        public function model(): \Illuminate\Database\Eloquent\Model
        {
            return $this->model;
        }
    };

    $userRepository->save(
        new $userDTO(
            name: 'John Doe',
        ),
    );

    $model->shouldHaveReceived('setAttribute')->with('name', 'John Doe');
    $model->shouldHaveReceived('save');
});
