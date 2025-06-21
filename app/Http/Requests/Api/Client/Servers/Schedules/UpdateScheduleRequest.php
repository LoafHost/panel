<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Schedules;

use LoafPanel\Models\Permission;

class UpdateScheduleRequest extends StoreScheduleRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SCHEDULE_UPDATE;
    }
}
