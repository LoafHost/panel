<?php

namespace LoafPanel\Http\Requests\Api\Application\Locations;

use LoafPanel\Services\Acl\Api\AdminAcl;
use LoafPanel\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteLocationRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_LOCATIONS;

    protected int $permission = AdminAcl::WRITE;
}
