<?php

namespace LoafPanel\Events\Auth;

use LoafPanel\Models\User;
use LoafPanel\Events\Event;

class DirectLogin extends Event
{
    public function __construct(public User $user, public bool $remember)
    {
    }
}
