<?php

namespace LoafPanel\Http\Requests\Api\Client\Servers;

use LoafPanel\Http\Requests\Api\Client\ClientApiRequest;

class GetServerRequest extends ClientApiRequest
{
    /**
     * Determine if a client has permission to view this server on the API. This
     * should never be false since this would be checking the same permission as
     * resourceExists().
     */
    public function authorize(): bool
    {
        return true;
    }
}
