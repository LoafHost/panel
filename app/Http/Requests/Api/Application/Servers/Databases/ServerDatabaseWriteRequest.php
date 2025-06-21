<?php

namespace LoafPanel\Http\Requests\Api\Application\Servers\Databases;

use LoafPanel\Services\Acl\Api\AdminAcl;

class ServerDatabaseWriteRequest extends GetServerDatabasesRequest
{
    protected int $permission = AdminAcl::WRITE;
}
