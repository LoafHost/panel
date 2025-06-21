<?php

namespace LoafPanel\Events\User;

use LoafPanel\Models\User;
use LoafPanel\Events\Event;
use Illuminate\Queue\SerializesModels;

class Deleted extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
    }
}
