<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Schedules;

use LoafPanel\Models\Permission;

class DeleteScheduleRequest extends ViewScheduleRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SCHEDULE_DELETE;
    }
}
