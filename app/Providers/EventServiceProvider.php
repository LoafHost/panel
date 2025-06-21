<?php

namespace LoafPanel\Providers;

use LoafPanel\Models\User;
use LoafPanel\Models\Server;
use LoafPanel\Models\Subuser;
use LoafPanel\Models\EggVariable;
use LoafPanel\Observers\UserObserver;
use LoafPanel\Observers\ServerObserver;
use LoafPanel\Observers\SubuserObserver;
use LoafPanel\Observers\EggVariableObserver;
use LoafPanel\Listeners\Auth\AuthenticationListener;
use LoafPanel\Events\Server\Installed as ServerInstalledEvent;
use LoafPanel\Notifications\ServerInstalled as ServerInstalledNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        ServerInstalledEvent::class => [ServerInstalledNotification::class],
    ];

    protected $subscribe = [
        AuthenticationListener::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        User::observe(UserObserver::class);
        Server::observe(ServerObserver::class);
        Subuser::observe(SubuserObserver::class);
        EggVariable::observe(EggVariableObserver::class);
    }
}
