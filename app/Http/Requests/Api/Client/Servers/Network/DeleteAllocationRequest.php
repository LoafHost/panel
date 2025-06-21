<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Network;

use LoafPanel\Models\Permission;
use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class DeleteAllocationRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_ALLOCATION_DELETE;
    }
}
