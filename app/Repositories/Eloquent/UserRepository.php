<?php

namespace LoafPanel\Repositories\Eloquent;

use LoafPanel\Models\User;
use LoafPanel\Contracts\Repository\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * Return the model backing this repository.
     */
    public function model(): string
    {
        return User::class;
    }
}
