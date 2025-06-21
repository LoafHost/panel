@extends('layouts.admin')

@section('title', 'Nodes')

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Nodes</h1>
    <p class="text-gray-400">All nodes available on the system for server deployment.</p>
@endsection

@section('content')
<div x-data="{
    filter: '{{ request()->input("filter.name") }}',
    nodes: {{ json_encode($nodes->items()) }},
    init() {
        this.nodes.forEach(node => this.pingNode(node));
        setInterval(() => {
            this.nodes.forEach(node => this.pingNode(node));
        }, 10000);
    },
    pingNode(node) {
        const statusElement = document.getElementById(`node-status-${node.id}`);
        if (!statusElement) return;

        fetch(`${node.scheme}://${node.fqdn}:${node.daemonListen}/api/system`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${node.token}`,
            },
            signal: AbortSignal.timeout(5000) // 5-second timeout
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            statusElement.innerHTML = `<i class="fas fa-heartbeat text-green-500" title="v${data.version}"></i>`;
        })
        .catch(error => {
            statusElement.innerHTML = `<i class="fas fa-heart-broken text-red-500" title="Error connecting to node. Check console for details."></i>`;
            console.error(`Error pinging node ${node.name}:`, error);
        });
    }
}">
    <div class="bg-gray-800 shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="text-lg text-white font-semibold">Node List</h3>
            <div class="flex items-center space-x-4">
                <form action="{{ route('admin.nodes') }}" method="GET" class="flex-grow md:flex-grow-0">
                    <div class="relative">
                        <input type="text" name="filter[name]" class="bg-gray-900 border-gray-700 text-white rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request()->input('filter.name') }}" placeholder="Search by name...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </form>
                <a href="{{ route('admin.nodes.new') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded whitespace-nowrap">Create New</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-white">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Memory</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Disk</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Servers</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">SSL</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Public</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse ($nodes as $node)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 text-center" id="node-status-{{ $node->id }}"><i class="fas fa-sync-alt fa-spin text-gray-500"></i></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($node->maintenance_mode)<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 text-yellow-800" title="Maintenance mode is enabled for this node."><i class="fas fa-wrench"></i></span>@endif
                                <a href="{{ route('admin.nodes.view', $node->id) }}" class="text-blue-400 hover:text-blue-300">{{ $node->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $node->location->short }}</td>
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $node->memory }} MB</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $node->disk }} MB</code></td>
                            <td class="px-6 py-4 text-center">{{ $node->servers_count }}</td>
                            <td class="px-6 py-4 text-center"><i class="fas fa-{{ $node->scheme === 'https' ? 'lock' : 'unlock' }}" @if($node->scheme === 'https') class="text-green-500" @else class="text-red-500" @endif></i></td>
                            <td class="px-6 py-4 text-center"><i class="fas fa-{{ $node->public ? 'eye' : 'eye-slash' }}"></i></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-400">No nodes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($nodes->hasPages())
            <div class="px-6 py-4 bg-gray-800 border-t border-gray-700">
                {{ $nodes->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    {{-- The old jQuery-based ping script has been replaced with an Alpine.js component. --}}
@endsection
