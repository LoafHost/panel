<?php

namespace LoafPanel\Facades;

use Illuminate\Support\Facades\Facade;
use LoafPanel\Services\Activity\ActivityLogTargetableService;

class LogTarget extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogTargetableService::class;
    }
}
