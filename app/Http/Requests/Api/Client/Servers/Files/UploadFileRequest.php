<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Files;

use LoafPanel\Models\Permission;
use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class UploadFileRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_FILE_CREATE;
    }
}
