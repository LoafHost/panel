<?php

namespace LoafPanel\Repositories\Wings;

use Webmozart\Assert\Assert;
use LoafPanel\Models\Server;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\TransferException;
use LoafPanel\Exceptions\Http\Connection\DaemonConnectionException;

/**
 * @method \LoafPanel\Repositories\Wings\DaemonPowerRepository setNode(\LoafPanel\Models\Node $node)
 * @method \LoafPanel\Repositories\Wings\DaemonPowerRepository setServer(\LoafPanel\Models\Server $server)
 */
class DaemonPowerRepository extends DaemonRepository
{
    /**
     * Sends a power action to the server instance.
     *
     * @throws DaemonConnectionException
     */
    public function send(string $action): ResponseInterface
    {
        Assert::isInstanceOf($this->server, Server::class);

        try {
            return $this->getHttpClient()->post(
                sprintf('/api/servers/%s/power', $this->server->uuid),
                ['json' => ['action' => $action]]
            );
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
