# Larepo - Laravel Repository

![Test build](https://github.com/mattb-it/larepo/actions/workflows/test.yml/badge.svg)
![Latest version](https://img.shields.io/github/release/mattb-it/larepo.svg?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)

## Introduction

This package is designed to help developers organize their code by offering a simplified approach to
implementing the Repository Pattern in Laravel. While it doesn’t follow the pattern in its purest form, it
provides a practical solution that aligns well with Laravel’s architecture. Eloquent, being as powerful as it
is, handles many database interactions behind the scenes, making a strict Repository Pattern not really useful.
Instead, this package focuses on helping you structure your code more effectively without adding
complexity.

With this package, you’ll find it easier to apply SOLID principles, search through your codebase for
operations like querying, deleting, or saving models, and simplify the way you manage models by introducing
DTOs (Data Transfer Objects). It’s especially useful for intermediate developers looking to build clean,
maintainable applications while keeping things simple.

## Getting started

### Installation

To install Larepo via Composer, run the following command:

```bash
composer require mattb-it/larepo
```

### Generate repository
Laravel projects typically include a `User` model by default. Let's generate a `UserRepository` using Larepo's artisan command:

```bash
php artisan larepo:make:repository User
```

This command will create a `UserRepository` class in `app/Repositories/UserRepository.php`:

```php
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Mattbit\Larepo\Repositories\EloquentRepository;

class UserRepository extends EloquentRepository
{
    public function model(): Model
    {
        return new User();
    }
}
```

### Generate model DTO
After generating the `UserRepository`, the next step is to create a corresponding DTO (Data Transfer Object) for the `User` model. The DTO is necessary for using the save method.

```php
php artisan larepo:make:dto User
```

This command generates the `UserDTO` class in `app/DTO/Models/UserDTO.php`:

```php
<?php

declare(strict_types=1);

namespace App\DTO\Models;

use Mattbit\Larepo\DTO\ModelDTOInterface;
use Mattbit\Larepo\Enums\Attribute;

readonly class UserDTO implements ModelDTOInterface
{
    public function __construct(
        public Attribute|int $id = Attribute::UNDEFINED,
    ) {}
}
```

Now that you've generated the `UserDTO`, you can populate it with the model's attributes. This will make saving the model easier. Here's an example DTO for the `User` model:

```php
<?php

declare(strict_types=1);

namespace App\DTO\Models;

use Mattbit\Larepo\DTO\ModelDTOInterface;
use Mattbit\Larepo\Enums\Attribute;

readonly class UserDTO implements ModelDTOInterface
{
    public function __construct(
        public Attribute|int $id = Attribute::UNDEFINED,
        public Attribute|string $name = Attribute::UNDEFINED,
        public Attribute|string $email = Attribute::UNDEFINED,
        public Attribute|string $password = Attribute::UNDEFINED,
        public Attribute|string $rememberToken = Attribute::UNDEFINED,
    ) {}
}
```

### Attribute enum
The `Attribute` enum allows you to control whether a model's attribute should be updated or left unchanged. This is particularly useful when handling user input. For instance, if the request doesn't contain the name field, you can pass `Attribute::UNDEFINED` to signal that the attribute should not be modified, rather than setting it to null.

Here's an example that demonstrates how this works:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Models\UserDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mattbit\Larepo\Enums\Attribute;

class UpdateUserController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(Request $request, User $user): RedirectResponse
    {
        $this->userRepository->save(
            modelDTO: new UserDTO(
                name: $request->input('name', Attribute::UNDEFINED),
                email: $request->input('email', Attribute::UNDEFINED),
            ),
            model: $user,
        );

        return response()->redirectTo('/users');
    }
}
```

In this example, if the `name` parameter is absent from the request, `Attribute::UNDEFINED` is passed. This tells the `save` method that the `name` field should not be updated. Without this enum, passing a missing parameter could inadvertently set the field to `null`.

## Methods

Larepo provides several methods to simplify common operations like querying, saving, and deleting models.

### find
The `find` method retrieves a specific model by its primary key (usually `id`) or by another attribute:

```php
// Find by primary key
$user = $this->userRepository->find(1);

// Find by a specific attribute (e.g., email)
$user = $this->userRepository->find('me@mattb.it', 'email');
```

If the model is not found, the method returns `null`.

### findOrFail

Similar to find, but throws a `ModelNotFoundException` if the model is not found:

```php
// Find by primary key
$user = $this->userRepository->findOrFail(1);

// Find by a specific attribute (e.g., email)
$user = $this->userRepository->findOrFail('me@mattb.it', 'email');
```

### all
The all method retrieves all models from the database:

```php
$users = $this->userRepository->all();
```

### query
The `query` method allows you to create a query builder instance, which you can use to apply conditions:

```php
$users = $this->userRepository
    ->query()
    ->where('active', true)
    ->where('role', 'admin')
    ->get();
```

### delete
The `delete` method deletes the specified model instance from the database. It returns a `bool` indicating success or failure:

```php
$this->userRepository->delete($user);
```

### save
The `save` method creates or updates a model using a DTO that `implements ModelDTOInterface`. Before using this method, ensure that you've generated the model's DTO by running the `php artisan larepo:make:dto User` command.

```php
$this->userRepository->save(
    dto: new UserDTO(
        name: 'John Doe',
    ),
    model: $user,
);
```

This method returns the updated model instance.
