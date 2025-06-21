@php
    /** @var \LoafPanel\Models\Server $server */
    $router = app('router');
@endphp
<div class="bg-gray-800 shadow-md rounded-lg p-4 mb-8">
    <ul class="flex flex-wrap items-center justify-start space-x-2">
        <li class="flex-1 min-w-0">
            <a href="{{ route('admin.servers.view', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <i class="fas fa-info-circle mr-1"></i> About
            </a>
        </li>
        @if($server->isInstalled())
            <li class="flex-1 min-w-0">
                <a href="{{ route('admin.servers.view.details', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.details') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i class="fas fa-list-ul mr-1"></i> Details
                </a>
            </li>
            <li class="flex-1 min-w-0">
                <a href="{{ route('admin.servers.view.build', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.build') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i class="fas fa-cogs mr-1"></i> Build
                </a>
            </li>
            <li class="flex-1 min-w-0">
                <a href="{{ route('admin.servers.view.startup', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.startup') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i class="fas fa-play-circle mr-1"></i> Startup
                </a>
            </li>
            <li class="flex-1 min-w-0">
                <a href="{{ route('admin.servers.view.database', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.database') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i class="fas fa-database mr-1"></i> Database
                </a>
            </li>
            <li class="flex-1 min-w-0">
                <a href="{{ route('admin.servers.view.mounts', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.mounts') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <i class="fas fa-hdd mr-1"></i> Mounts
                </a>
            </li>
        @endif
        <li class="flex-1 min-w-0">
            <a href="{{ route('admin.servers.view.manage', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.manage') ? 'bg-primary-500 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                <i class="fas fa-user-shield mr-1"></i> Manage
            </a>
        </li>
        <li class="flex-1 min-w-0">
            <a href="{{ route('admin.servers.view.delete', $server->id) }}" class="text-center block rounded py-2 px-4 text-sm truncate {{ $router->currentRouteNamed('admin.servers.view.delete') ? 'bg-red-500 text-white' : 'text-gray-300 hover:bg-red-700' }}">
                <i class="fas fa-trash-alt mr-1"></i> Delete
            </a>
        </li>
        <li class="flex-shrink-0 ml-auto">
            <a href="/server/{{ $server->uuidShort }}" target="_blank" class="text-center block rounded py-2 px-4 text-sm text-gray-300 hover:bg-gray-700">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </li>
    </ul>
</div>
