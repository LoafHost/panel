<?php

namespace LoafPanel\Http\Controllers\Api\Application\Servers;

use LoafPanel\Models\User;
use LoafPanel\Models\Server;
use LoafPanel\Services\Servers\StartupModificationService;
use LoafPanel\Transformers\Api\Application\ServerTransformer;
use LoafPanel\Http\Controllers\Api\Application\ApplicationApiController;
use LoafPanel\Http\Requests\Api\Application\Servers\UpdateServerStartupRequest;

class StartupController extends ApplicationApiController
{
    /**
     * StartupController constructor.
     */
    public function __construct(private StartupModificationService $modificationService)
    {
        parent::__construct();
    }

    /**
     * Update the startup and environment settings for a specific server.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \LoafPanel\Exceptions\Http\Connection\DaemonConnectionException
     * @throws \LoafPanel\Exceptions\Model\DataValidationException
     * @throws \LoafPanel\Exceptions\Repository\RecordNotFoundException
     */
    public function index(UpdateServerStartupRequest $request, Server $server): array
    {
        $server = $this->modificationService
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle($server, $request->validated());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
