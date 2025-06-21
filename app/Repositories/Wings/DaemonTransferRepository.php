<?php

namespace LoafPanel\Repositories\Wings;

use LoafPanel\Models\Node;
use Lcobucci\JWT\Token\Plain;
use GuzzleHttp\Exception\GuzzleException;
use LoafPanel\Exceptions\Http\Connection\DaemonConnectionException;

/**
 * @method \LoafPanel\Repositories\Wings\DaemonTransferRepository setNode(\LoafPanel\Models\Node $node)
 * @method \LoafPanel\Repositories\Wings\DaemonTransferRepository setServer(\LoafPanel\Models\Server $server)
 */
class DaemonTransferRepository extends DaemonRepository
{
    /**
     * @throws DaemonConnectionException
     */
    public function notify(Node $targetNode, Plain $token): void
    {
        try {
            $this->getHttpClient()->post(sprintf('/api/servers/%s/transfer', $this->server->uuid), [
                'json' => [
                    'server_id' => $this->server->uuid,
                    'url' => $targetNode->getConnectionAddress() . '/api/transfers',
                    'token' => 'Bearer ' . $token->toString(),
                    'server' => [
                        'uuid' => $this->server->uuid,
                        'start_on_completion' => false,
                    ],
                ],
            ]);
        } catch (GuzzleException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }
}
