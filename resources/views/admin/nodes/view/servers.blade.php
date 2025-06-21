@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Servers
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">{{ $node->name }}<small class="text-gray-400"> :: Servers</small></h1>
    <p class="text-sm text-gray-400">All servers currently assigned to this node.</p>
@endsection

@section('content')
<div class="mt-8">
    <div class="flex items-center mb-4 text-sm text-gray-400">
        <a href="{{ route('admin.nodes.view', $node->id) }}" class="hover:text-white">About</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="hover:text-white">Settings</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="hover:text-white">Configuration</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="hover:text-white">Allocation</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="text-white font-medium">Servers</a>
    </div>

    <div x-data="{ search: '' }">
        <div class="mb-4">
            <input type="text" x-model="search" class="w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search servers...">
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-white mb-4">Process Manager</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-900">
                        <tr>
                            <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Server Name</th>
                            <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Owner</th>
                            <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Service</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($servers as $server)
                            <tr x-show="search === '' || '{{ $server->name }}'.toLowerCase().includes(search.toLowerCase()) || '{{ $server->uuidShort }}'.toLowerCase().includes(search.toLowerCase()) || '{{ $server->user->username }}'.toLowerCase().includes(search.toLowerCase())" data-server="{{ $server->uuid }}">
                                <td class="p-3 whitespace-nowrap text-sm text-gray-300"><code>{{ $server->uuidShort }}</code></td>
                                <td class="p-3 whitespace-nowrap text-sm"><a href="{{ route('admin.servers.view', $server->id) }}" class="text-indigo-400 hover:text-indigo-300">{{ $server->name }}</a></td>
                                <td class="p-3 whitespace-nowrap text-sm"><a href="{{ route('admin.users.view', $server->owner_id) }}" class="text-indigo-400 hover:text-indigo-300">{{ $server->user->username }}</a></td>
                                <td class="p-3 whitespace-nowrap text-sm text-gray-300">{{ $server->nest->name }} ({{ $server->egg->name }})</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($servers->hasPages())
                <div class="mt-4 bg-gray-800 px-4 py-3 flex items-center justify-between sm:px-6">
                    {{ $servers->render() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
