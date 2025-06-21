<?php

namespace LoafPanel\Repositories\Eloquent;

use LoafPanel\Models\RecoveryToken;

class RecoveryTokenRepository extends EloquentRepository
{
    public function model(): string
    {
        return RecoveryToken::class;
    }
}
