<?php

namespace LoafPanel\Events\Subuser;

use LoafPanel\Events\Event;
use LoafPanel\Models\Subuser;
use Illuminate\Queue\SerializesModels;

class Created extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Subuser $subuser)
    {
    }
}
