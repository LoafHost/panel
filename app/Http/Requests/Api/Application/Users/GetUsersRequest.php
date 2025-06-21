<?php

namespace LoafPanel\Http\Requests\Api\Application\Users;

use LoafPanel\Services\Acl\Api\AdminAcl as Acl;
use LoafPanel\Http\Requests\Api\Application\ApplicationApiRequest;

class GetUsersRequest extends ApplicationApiRequest
{
    protected ?string $resource = Acl::RESOURCE_USERS;

    protected int $permission = Acl::READ;
}
