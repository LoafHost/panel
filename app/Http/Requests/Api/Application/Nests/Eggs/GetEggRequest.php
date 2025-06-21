<?php

namespace LoafPanel\Http\Requests\Api\Application\Nests\Eggs;

use LoafPanel\Services\Acl\Api\AdminAcl;
use LoafPanel\Http\Requests\Api\Application\ApplicationApiRequest;

class GetEggRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_EGGS;

    protected int $permission = AdminAcl::READ;
}
