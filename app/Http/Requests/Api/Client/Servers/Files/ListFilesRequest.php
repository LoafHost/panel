<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers\Files;

use LoafPanel\Models\Permission;
use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class ListFilesRequest extends ClientApiRequest
{
    /**
     * Check that the user making this request to the API is authorized to list all
     * the files that exist for a given server.
     */
    public function permission(): string
    {
        return Permission::ACTION_FILE_READ;
    }

    public function rules(): array
    {
        return [
            'directory' => 'sometimes|nullable|string',
        ];
    }
}
