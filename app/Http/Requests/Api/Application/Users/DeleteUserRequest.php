<?php

namespace LoafPanel\Http\Requests\Api\Application\Users;

use LoafPanel\Services\Acl\Api\AdminAcl;
use LoafPanel\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteUserRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_USERS;

    protected int $permission = AdminAcl::WRITE;
}
