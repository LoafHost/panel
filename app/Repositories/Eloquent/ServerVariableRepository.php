<?php

namespace LoafPanel\Repositories\Eloquent;

use LoafPanel\Models\ServerVariable;
use LoafPanel\Contracts\Repository\ServerVariableRepositoryInterface;

class ServerVariableRepository extends EloquentRepository implements ServerVariableRepositoryInterface
{
    /**
     * Return the model backing this repository.
     */
    public function model(): string
    {
        return ServerVariable::class;
    }
}
