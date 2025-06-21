@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $server->name }}<small class="text-gray-400 ml-2">{{ str_limit($server->description) }}</small></h1>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="grid grid-cols-12 gap-8" x-data="{
    transferModal: false,
    server: {{ $server->toJson() }}
}">
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-white">
                <div>
                    <p class="text-gray-400">Internal ID</p>
                    <p class="font-mono bg-gray-900 rounded px-2 py-1 inline-block">{{ $server->id }}</p>
                </div>
                <div>
                    <p class="text-gray-400">External ID</p>
                    @if(is_null($server->external_id))
                        <p><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-700 text-gray-300">Not Set</span></p>
                    @else
                        <p class="font-mono bg-gray-900 rounded px-2 py-1 inline-block">{{ $server->external_id }}</p>
                    @endif
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400">UUID / Docker Container ID</p>
                    <p class="font-mono bg-gray-900 rounded px-2 py-1 inline-block">{{ $server->uuid }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Current Egg</p>
                    <p>
                        <a href="{{ route('admin.nests.view', $server->nest_id) }}" class="text-primary-400 hover:text-primary-300 transition duration-150 ease-in-out">{{ $server->nest->name }}</a> ::
                        <a href="{{ route('admin.nests.egg.view', $server->egg_id) }}" class="text-primary-400 hover:text-primary-300 transition duration-150 ease-in-out">{{ $server->egg->name }}</a>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400">Server Name</p>
                    <p>{{ $server->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">CPU Limit</p>
                    <p class="font-mono">{{ $server->cpu === 0 ? 'Unlimited' : $server->cpu . '%' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">CPU Pinning</p>
                    <p>
                        @if($server->threads != null)
                            <span class="font-mono">{{ $server->threads }}</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-700 text-gray-300">Not Set</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-400">Memory</p>
                    <p class="font-mono">
                        {{ $server->memory === 0 ? 'Unlimited' : $server->memory . ' MiB' }}
                        <span class="text-gray-400">(Swap: {{ $server->swap === 0 ? 'Not Set' : ($server->swap === -1 ? 'Unlimited' : $server->swap . ' MiB') }})</span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400">Disk Space</p>
                    <p class="font-mono">{{ $server->disk === 0 ? 'Unlimited' : $server->disk . ' MiB' }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-4">
        <div class="bg-gray-800 shadow-md rounded-lg">
            <div class="p-6">
                <h3 class="text-xl text-white font-semibold mb-4">Control</h3>
                <p class="text-gray-400 mb-2">Node: <a href="{{ route('admin.nodes.view', $server->node->id) }}" class="text-primary-400 hover:text-primary-300">{{ $server->node->name }}</a></p>
                <p class="text-gray-400">Owner: <a href="{{ route('admin.users.view', $server->user->id) }}" class="text-primary-400 hover:text-primary-300">{{ $server->user->email }}</a></p>
                <div class="mt-6 space-y-2">
                    <a href="{{ route('server.index', $server->uuidShort) }}" class="btn bg-primary-500 hover:bg-primary-600 text-white w-full">Manage in Frontend</a>
                    <button @click="transferModal = true" class="btn bg-gray-600 hover:bg-gray-500 text-white w-full">Transfer Server</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Modal -->
    <div x-show="transferModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="transferModal" @click.away="transferModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="transferModal" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'/admin/servers/view/' + server.id + '/transfer'" method="POST">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Transfer Server</h3>
                        <div class="mt-4">
                            <p class="text-sm text-gray-400">This will transfer the server to a new node, potentially changing the service IP and port. Any data will be moved automatically.</p>
                            <div class="mt-4">
                                <label for="newNode" class="block text-sm font-medium text-gray-300">New Node</label>
                                <select name="node_id" id="newNode" class="form-select mt-1 block w-full">
                                    @foreach($nodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->name }} ({{ $node->allocations->where('server_id', null)->count() }} available)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="newAllocation" class="block text-sm font-medium text-gray-300">New Default Allocation</label>
                                <select name="allocation_id" id="newAllocation" class="form-select mt-1 block w-full"></select>
                            </div>
                            <div class="mt-4">
                                <label for="additionalAllocations" class="block text-sm font-medium text-gray-300">Additional Allocations</label>
                                <select name="additional_allocations[]" id="additionalAllocations" class="form-select mt-1 block w-full" multiple></select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @csrf
                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white">Initiate Transfer</button>
                        <button type="button" @click="transferModal = false" class="btn bg-gray-600 hover:bg-gray-500 text-white mr-2">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nodeSelect = document.getElementById('newNode');
        const allocationSelect = document.getElementById('newAllocation');
        const additionalAllocationsSelect = document.getElementById('additionalAllocations');

        function updateAllocations() {
            const nodeId = nodeSelect.value;
            if (!nodeId) return;

            fetch(`/admin/nodes/${nodeId}/allocations`)
                .then(response => response.json())
                .then(data => {
                    allocationSelect.innerHTML = '';
                    additionalAllocationsSelect.innerHTML = '';

                    data.forEach(alloc => {
                        if(alloc.server_id === null) {
                            const option = new Option(`${alloc.ip}:${alloc.port}`, alloc.id);
                            allocationSelect.add(option);
                            additionalAllocationsSelect.add(option.cloneNode(true));
                        }
                    });
                });
        }

        nodeSelect.addEventListener('change', updateAllocations);
        updateAllocations(); // Initial load
    });
</script>
@endpush
