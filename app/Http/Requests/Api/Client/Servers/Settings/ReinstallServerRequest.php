<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Settings;

use LoafPanel\Models\Permission;
use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class ReinstallServerRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SETTINGS_REINSTALL;
    }
}
