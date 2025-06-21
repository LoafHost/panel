<?php

namespace LoafPanel\Contracts\Core;

use LoafPanel\Events\Event;

interface ReceivesEvents
{
    /**
     * Handles receiving an event from the application.
     */
    public function handle(Event $notification): void;
}
