<?php

namespace LoafPanel\Events\Server;

use LoafPanel\Events\Event;
use LoafPanel\Models\Server;
use Illuminate\Queue\SerializesModels;

class Installed extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Server $server)
    {
    }
}
