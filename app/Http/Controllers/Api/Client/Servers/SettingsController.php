<?php

namespace LoafPanel\Http\Controllers\Api\Client\Servers;

use Illuminate\Http\Response;
use LoafPanel\Models\Server;
use Illuminate\Http\JsonResponse;
use LoafPanel\Facades\Activity;
use LoafPanel\Repositories\Eloquent\ServerRepository;
use LoafPanel\Services\Servers\ReinstallServerService;
use LoafPanel\Http\Controllers\Api\Client\ClientApiController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use LoafPanel\Http\Requests\Api\Client\Servers\Settings\RenameServerRequest;
use LoafPanel\Http\Requests\Api\Client\Servers\Settings\SetDockerImageRequest;
use LoafPanel\Http\Requests\Api\Client\Servers\Settings\ReinstallServerRequest;

class SettingsController extends ClientApiController
{
    /**
     * SettingsController constructor.
     */
    public function __construct(
        private ServerRepository $repository,
        private ReinstallServerService $reinstallServerService,
    ) {
        parent::__construct();
    }

    /**
     * Renames a server.
     *
     * @throws \LoafPanel\Exceptions\Model\DataValidationException
     * @throws \LoafPanel\Exceptions\Repository\RecordNotFoundException
     */
    public function rename(RenameServerRequest $request, Server $server): JsonResponse
    {
        $name = $request->input('name');
        $description = $request->has('description') ? (string) $request->input('description') : $server->description;
        $this->repository->update($server->id, [
            'name' => $name,
            'description' => $description,
        ]);

        if ($server->name !== $name) {
            Activity::event('server:settings.rename')
                ->property(['old' => $server->name, 'new' => $name])
                ->log();
        }

        if ($server->description !== $description) {
            Activity::event('server:settings.description')
                ->property(['old' => $server->description, 'new' => $description])
                ->log();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Reinstalls the server on the daemon.
     *
     * @throws \Throwable
     */
    public function reinstall(ReinstallServerRequest $request, Server $server): JsonResponse
    {
        $this->reinstallServerService->handle($server);

        Activity::event('server:reinstall')->log();

        return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    /**
     * Changes the Docker image in use by the server.
     *
     * @throws \Throwable
     */
    public function dockerImage(SetDockerImageRequest $request, Server $server): JsonResponse
    {
        if (!in_array($server->image, array_values($server->egg->docker_images))) {
            throw new BadRequestHttpException('This server\'s Docker image has been manually set by an administrator and cannot be updated.');
        }

        $original = $server->image;
        $server->forceFill(['image' => $request->input('docker_image')])->saveOrFail();

        if ($original !== $server->image) {
            Activity::event('server:startup.image')
                ->property(['old' => $original, 'new' => $request->input('docker_image')])
                ->log();
        }

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
