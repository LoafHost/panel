<?php

namespace LoafPanel\Http\Requests\Api\Application\Nodes;

use LoafPanel\Services\Acl\Api\AdminAcl;
use LoafPanel\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteNodeRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_NODES;

    protected int $permission = AdminAcl::WRITE;
}
