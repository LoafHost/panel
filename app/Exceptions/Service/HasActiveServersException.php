<?php

namespace LoafPanel\Exceptions\Service;

use Illuminate\Http\Response;
use LoafPanel\Exceptions\DisplayException;

class HasActiveServersException extends DisplayException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
