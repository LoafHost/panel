<?php

namespace LoafPanel\Facades;

use Illuminate\Support\Facades\Facade;
use LoafPanel\Services\Activity\ActivityLogService;

class Activity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogService::class;
    }
}
