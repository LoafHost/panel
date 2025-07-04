<?php

namespace LoafPanel\Listeners\Auth;

use LoafPanel\Facades\Activity;
use Illuminate\Auth\Events\Failed;
use LoafPanel\Events\Auth\DirectLogin;
use Illuminate\Contracts\Events\Dispatcher;
use LoafPanel\Extensions\Illuminate\Events\Contracts\SubscribesToEvents;

class AuthenticationListener implements SubscribesToEvents
{
    /**
     * Handles an authentication event by logging the user and information about
     * the request.
     */
    public function handle(Failed|DirectLogin $event): void
    {
        $activity = Activity::withRequestMetadata();
        if ($event->user) {
            $activity = $activity->subject($event->user);
        }

        if ($event instanceof Failed) {
            foreach ($event->credentials as $key => $value) {
                $activity = $activity->property($key, $value);
            }
        }

        $activity->event($event instanceof Failed ? 'auth:fail' : 'auth:success')->log();
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Failed::class, self::class);
        $events->listen(DirectLogin::class, self::class);
    }
}
