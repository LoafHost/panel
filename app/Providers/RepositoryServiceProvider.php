<?php

namespace LoafPanel\Providers;

use Illuminate\Support\ServiceProvider;
use LoafPanel\Repositories\Eloquent\EggRepository;
use LoafPanel\Repositories\Eloquent\NestRepository;
use LoafPanel\Repositories\Eloquent\NodeRepository;
use LoafPanel\Repositories\Eloquent\TaskRepository;
use LoafPanel\Repositories\Eloquent\UserRepository;
use LoafPanel\Repositories\Eloquent\ApiKeyRepository;
use LoafPanel\Repositories\Eloquent\ServerRepository;
use LoafPanel\Repositories\Eloquent\SessionRepository;
use LoafPanel\Repositories\Eloquent\SubuserRepository;
use LoafPanel\Repositories\Eloquent\DatabaseRepository;
use LoafPanel\Repositories\Eloquent\LocationRepository;
use LoafPanel\Repositories\Eloquent\ScheduleRepository;
use LoafPanel\Repositories\Eloquent\SettingsRepository;
use LoafPanel\Repositories\Eloquent\AllocationRepository;
use LoafPanel\Contracts\Repository\EggRepositoryInterface;
use LoafPanel\Repositories\Eloquent\EggVariableRepository;
use LoafPanel\Contracts\Repository\NestRepositoryInterface;
use LoafPanel\Contracts\Repository\NodeRepositoryInterface;
use LoafPanel\Contracts\Repository\TaskRepositoryInterface;
use LoafPanel\Contracts\Repository\UserRepositoryInterface;
use LoafPanel\Repositories\Eloquent\DatabaseHostRepository;
use LoafPanel\Contracts\Repository\ApiKeyRepositoryInterface;
use LoafPanel\Contracts\Repository\ServerRepositoryInterface;
use LoafPanel\Repositories\Eloquent\ServerVariableRepository;
use LoafPanel\Contracts\Repository\SessionRepositoryInterface;
use LoafPanel\Contracts\Repository\SubuserRepositoryInterface;
use LoafPanel\Contracts\Repository\DatabaseRepositoryInterface;
use LoafPanel\Contracts\Repository\LocationRepositoryInterface;
use LoafPanel\Contracts\Repository\ScheduleRepositoryInterface;
use LoafPanel\Contracts\Repository\SettingsRepositoryInterface;
use LoafPanel\Contracts\Repository\AllocationRepositoryInterface;
use LoafPanel\Contracts\Repository\EggVariableRepositoryInterface;
use LoafPanel\Contracts\Repository\DatabaseHostRepositoryInterface;
use LoafPanel\Contracts\Repository\ServerVariableRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register all the repository bindings.
     */
    public function register(): void
    {
        // Eloquent Repositories
        $this->app->bind(AllocationRepositoryInterface::class, AllocationRepository::class);
        $this->app->bind(ApiKeyRepositoryInterface::class, ApiKeyRepository::class);
        $this->app->bind(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->bind(DatabaseHostRepositoryInterface::class, DatabaseHostRepository::class);
        $this->app->bind(EggRepositoryInterface::class, EggRepository::class);
        $this->app->bind(EggVariableRepositoryInterface::class, EggVariableRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(NestRepositoryInterface::class, NestRepository::class);
        $this->app->bind(NodeRepositoryInterface::class, NodeRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(ServerRepositoryInterface::class, ServerRepository::class);
        $this->app->bind(ServerVariableRepositoryInterface::class, ServerVariableRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class, SettingsRepository::class);
        $this->app->bind(SubuserRepositoryInterface::class, SubuserRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
