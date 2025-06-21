<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Databases;

use LoafPanel\Models\Permission;
use LoafPanel\Contracts\Http\ClientPermissionsRequest;
use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class DeleteDatabaseRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_DATABASE_DELETE;
    }
}
