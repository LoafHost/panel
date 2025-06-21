<?php

namespace LoafPanel\Http\Controllers\Api\Application\Servers;

use Illuminate\Http\Response;
use LoafPanel\Models\Server;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use LoafPanel\Services\Servers\ServerCreationService;
use LoafPanel\Services\Servers\ServerDeletionService;
use LoafPanel\Transformers\Api\Application\ServerTransformer;
use LoafPanel\Http\Requests\Api\Application\Servers\GetServerRequest;
use LoafPanel\Http\Requests\Api\Application\Servers\GetServersRequest;
use LoafPanel\Http\Requests\Api\Application\Servers\ServerWriteRequest;
use LoafPanel\Http\Requests\Api\Application\Servers\StoreServerRequest;
use LoafPanel\Http\Controllers\Api\Application\ApplicationApiController;

class ServerController extends ApplicationApiController
{
    /**
     * ServerController constructor.
     */
    public function __construct(
        private ServerCreationService $creationService,
        private ServerDeletionService $deletionService,
    ) {
        parent::__construct();
    }

    /**
     * Return all the servers that currently exist on the Panel.
     */
    public function index(GetServersRequest $request): array
    {
        $servers = QueryBuilder::for(Server::query())
            ->allowedFilters(['uuid', 'uuidShort', 'name', 'description', 'image', 'external_id'])
            ->allowedSorts(['id', 'uuid'])
            ->paginate($request->query('per_page') ?? 50);

        return $this->fractal->collection($servers)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Create a new server on the system.
     *
     * @throws \Throwable
     * @throws \Illuminate\Validation\ValidationException
     * @throws \LoafPanel\Exceptions\DisplayException
     * @throws \LoafPanel\Exceptions\Model\DataValidationException
     * @throws \LoafPanel\Exceptions\Repository\RecordNotFoundException
     * @throws \LoafPanel\Exceptions\Service\Deployment\NoViableAllocationException
     * @throws \LoafPanel\Exceptions\Service\Deployment\NoViableNodeException
     */
    public function store(StoreServerRequest $request): JsonResponse
    {
        $server = $this->creationService->handle($request->validated(), $request->getDeploymentObject());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->respond(201);
    }

    /**
     * Show a single server transformed for the application API.
     */
    public function view(GetServerRequest $request, Server $server): array
    {
        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Deletes a server.
     *
     * @throws \LoafPanel\Exceptions\DisplayException
     */
    public function delete(ServerWriteRequest $request, Server $server, string $force = ''): Response
    {
        $this->deletionService->withForce($force === 'force')->handle($server);

        return $this->returnNoContent();
    }
}
