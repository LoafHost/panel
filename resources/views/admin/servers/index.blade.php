@extends('layouts.modern')

@section('title')
    List Servers
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Servers<small class="text-gray-400">All servers available on the system.</small></h1>
@endsection

@section('content')
<div x-data="{ filter: '{{ request()->input("filter")["*"] ?? '' }}' }">
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12">
            <div class="bg-gray-800 shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg text-white font-semibold">Server List</h3>
                    <div class="space-x-2 flex items-center">
                        <form action="{{ route('admin.servers') }}" method="GET" class="flex items-center">
                            <input type="text" name="filter[*]" class="form-input bg-gray-900 border-gray-700 text-white rounded-md shadow-sm" x-model="filter" placeholder="Search Servers">
                            <button type="submit" class="btn bg-primary-500 hover:bg-primary-600 text-white ml-2 rounded-md"><i class="fas fa-search"></i></button>
                        </form>
                        <a href="{{ route('admin.servers.new') }}" class="btn bg-green-500 hover:bg-green-600 text-white ml-2 rounded-md">Create New</a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-white">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Server Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">UUID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Owner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Node</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Connection</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach ($servers as $server)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.servers.view', $server->id) }}" class="text-primary-400 hover:text-primary-300">{{ $server->name }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-900 p-1 rounded">{{ $server->uuid }}</code></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.users.view', $server->user->id) }}" class="text-primary-400 hover:text-primary-300">{{ $server->user->username }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.nodes.view', $server->node->id) }}" class="text-primary-400 hover:text-primary-300">{{ $server->node->name }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="bg-gray-900 p-1 rounded">{{ $server->allocation->alias }}:{{ $server->allocation->port }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($server->isSuspended())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                                        @elseif(! $server->isInstalled())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Installing</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a class="btn btn-xs bg-blue-500 hover:bg-blue-600 text-white rounded-md px-2 py-1" href="/server/{{ $server->uuidShort }}"><i class="fas fa-wrench"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($servers->hasPages())
                    <div class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                        <div class="pagination-dark">
                            {!! $servers->appends(['filter' => Request::input('filter')])->render() !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
