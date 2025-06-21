<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Subusers;

use LoafPanel\Models\Permission;

class DeleteSubuserRequest extends SubuserRequest
{
    public function permission(): string
    {
        return Permission::ACTION_USER_DELETE;
    }
}
