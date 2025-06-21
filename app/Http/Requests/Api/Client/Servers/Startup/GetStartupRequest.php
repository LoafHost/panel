<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Startup;

use LoafPanel\Models\Permission;
use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class GetStartupRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_STARTUP_READ;
    }
}
