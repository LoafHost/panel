<?php

namespace LoafPanel\Http\Controllers\Api\Application\Servers;

use LoafPanel\Models\Server;
use LoafPanel\Services\Servers\BuildModificationService;
use LoafPanel\Services\Servers\DetailsModificationService;
use LoafPanel\Transformers\Api\Application\ServerTransformer;
use LoafPanel\Http\Controllers\Api\Application\ApplicationApiController;
use LoafPanel\Http\Requests\Api\Application\Servers\UpdateServerDetailsRequest;
use LoafPanel\Http\Requests\Api\Application\Servers\UpdateServerBuildConfigurationRequest;

class ServerDetailsController extends ApplicationApiController
{
    /**
     * ServerDetailsController constructor.
     */
    public function __construct(
        private BuildModificationService $buildModificationService,
        private DetailsModificationService $detailsModificationService,
    ) {
        parent::__construct();
    }

    /**
     * Update the details for a specific server.
     *
     * @throws \LoafPanel\Exceptions\DisplayException
     * @throws \LoafPanel\Exceptions\Model\DataValidationException
     * @throws \LoafPanel\Exceptions\Repository\RecordNotFoundException
     */
    public function details(UpdateServerDetailsRequest $request, Server $server): array
    {
        $updated = $this->detailsModificationService->returnUpdatedModel()->handle(
            $server,
            $request->validated()
        );

        return $this->fractal->item($updated)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }

    /**
     * Update the build details for a specific server.
     *
     * @throws \LoafPanel\Exceptions\DisplayException
     * @throws \LoafPanel\Exceptions\Model\DataValidationException
     * @throws \LoafPanel\Exceptions\Repository\RecordNotFoundException
     */
    public function build(UpdateServerBuildConfigurationRequest $request, Server $server): array
    {
        $server = $this->buildModificationService->handle($server, $request->validated());

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->toArray();
    }
}
