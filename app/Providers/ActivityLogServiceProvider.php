<?php

namespace LoafPanel\Providers;

use Illuminate\Support\ServiceProvider;
use LoafPanel\Services\Activity\ActivityLogBatchService;
use LoafPanel\Services\Activity\ActivityLogTargetableService;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Registers the necessary activity logger singletons scoped to the individual
     * request instances.
     */
    public function register()
    {
        $this->app->scoped(ActivityLogBatchService::class);
        $this->app->scoped(ActivityLogTargetableService::class);
    }
}
