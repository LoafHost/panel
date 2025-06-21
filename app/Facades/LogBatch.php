<?php

namespace LoafPanel\Facades;

use Illuminate\Support\Facades\Facade;
use LoafPanel\Services\Activity\ActivityLogBatchService;

class LogBatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityLogBatchService::class;
    }
}
