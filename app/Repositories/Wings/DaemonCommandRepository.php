<?php

namespace LoafPanel\Repositories\Wings;

use Webmozart\Assert\Assert;
use LoafPanel\Models\Server;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\TransferException;
use LoafPanel\Exceptions\Http\Connection\DaemonConnectionException;

/**
 * @method \LoafPanel\Repositories\Wings\DaemonCommandRepository setNode(\LoafPanel\Models\Node $node)
 * @method \LoafPanel\Repositories\Wings\DaemonCommandRepository setServer(\LoafPanel\Models\Server $server)
 */
class DaemonCommandRepository extends DaemonRepository
{
    /**
     * Sends a command or multiple commands to a running server instance.
     *
     * @throws DaemonConnectionException
     */
    public function send(array|string $command): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/commands', $this->server->uuid),
                [
                    'json' => ['commands' => is_array($command) ? $command : [$command]],
                ]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
