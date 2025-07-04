<?php

namespace LoafPanel\Http\Controllers\Api\Application\Servers;

use LoafPanel\Models\Server;
use LoafPanel\Transformers\Api\Application\ServerTransformer;
use LoafPanel\Http\Controllers\Api\Application\ApplicationApiController;
use LoafPanel\Http\Requests\Api\Application\Servers\GetExternalServerRequest;

class ExternalServerController extends ApplicationApiController
{
    /**
     * Retrieve a specific server from the database using its external ID.
     */
    public function index(GetExternalServerRequest $request, string $external_id): array
    {
        $server = Server::query()->where('external_id', $external_id)->firstOrFail();

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
