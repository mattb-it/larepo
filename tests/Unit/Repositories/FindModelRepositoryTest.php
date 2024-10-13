<?php

it('can find using key', function () {
    $userRepository = new class extends \Mattbit\Larepo\Repositories\EloquentRepository {
        public function model(): \Illuminate\Database\Eloquent\Model
        {
            return new class extends \Illuminate\Database\Eloquent\Model {
                public function newQuery()
                {
                    $foundModel = new class extends \Illuminate\Database\Eloquent\Model {};
                    $foundModel->id = 10;

                    $builder = \Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                    $builder->shouldReceive('when')->times(2)->andReturnSelf();
                    $builder->shouldReceive('first')->andReturn($foundModel);

                    return $builder;
                }
            };
        }
    };

    $this->assertTrue($userRepository->find(10)?->id === 10);
});

it('throws ModelNotFoundException', function () {
    $userRepository = new class extends \Mattbit\Larepo\Repositories\EloquentRepository {
        public function model(): \Illuminate\Database\Eloquent\Model
        {
            return new class extends \Illuminate\Database\Eloquent\Model {
                public function newQuery()
                {
                    $builder = \Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                    $builder->shouldReceive('when')->times(2)->andReturnSelf();
                    $builder->shouldReceive('first')->andReturnNull();

                    return $builder;
                }
            };
        }
    };

    $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    $userRepository->findOrFail(10);
});
