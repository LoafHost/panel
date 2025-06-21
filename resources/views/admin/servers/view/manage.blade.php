@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Manage
@endsection

@section('content')
<div x-data="manageServer()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">{{ $server->name }}: Manage</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Reinstall Server -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Reinstall Server</h3>
            <p class="text-gray-400 mb-4">This will reinstall the server with the assigned service scripts. <strong class="text-red-400">Danger!</strong> This could overwrite server data.</p>
            @if($server->isInstalled())
                <button @click="openReinstallModal()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md">Reinstall Server</button>
            @else
                <button class="w-full bg-red-600 text-white font-bold py-2 px-4 rounded-md opacity-50 cursor-not-allowed" disabled>Server Must Be Installed to Reinstall</button>
            @endif
        </div>

        <!-- Install Status -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Install Status</h3>
            <p class="text-gray-400 mb-4">Toggle the install status from uninstalled to installed, or vice versa.</p>
            <button @click="openToggleModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">Toggle Install Status</button>
        </div>

        <!-- Suspension Status -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            @if(! $server->isSuspended())
                <h3 class="text-lg font-semibold text-white mb-4">Suspend Server</h3>
                <p class="text-gray-400 mb-4">This will suspend the server, stop any running processes, and block user access.</p>
                <button @click="openSuspendModal('suspend')" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-md @if(! is_null($server->transfer)) opacity-50 cursor-not-allowed @endif" @if(! is_null($server->transfer)) disabled @endif>Suspend Server</button>
            @else
                <h3 class="text-lg font-semibold text-white mb-4">Unsuspend Server</h3>
                <p class="text-gray-400 mb-4">This will unsuspend the server and restore normal user access.</p>
                <button @click="openSuspendModal('unsuspend')" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">Unsuspend Server</button>
            @endif
        </div>

        <!-- Transfer Server -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Transfer Server</h3>
            @if(is_null($server->transfer))
                <p class="text-gray-400 mb-4">Transfer this server to another node. <strong class="text-yellow-400">Warning:</strong> This feature is experimental.</p>
                @if($canTransfer)
                    <button @click="openTransferModal()" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md">Transfer Server</button>
                @else
                    <button class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-md opacity-50 cursor-not-allowed" disabled>Transfer Server</button>
                    <p class="text-sm text-gray-500 mt-2">Transferring a server requires more than one node to be configured.</p>
                @endif
            @else
                <p class="text-gray-400">This server is currently being transferred. Initiated at: <strong>{{ $server->transfer->created_at }}</strong></p>
                <div class="mt-6">
                    <button class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-md opacity-50 cursor-not-allowed" disabled>Transfer Server</button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <!-- Reinstall Modal -->
    <div x-show="reinstallModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="reinstallUrl" method="POST">
                    {!! csrf_field() !!}
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-white">Reinstall Server</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">Are you sure you want to reinstall this server? This will overwrite any existing data.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Reinstall</button>
                        <button @click="reinstallModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toggle Install Modal -->
    <div x-show="toggleModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="toggleUrl" method="POST">
                    {!! csrf_field() !!}
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa fa-exchange-alt text-blue-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-white">Toggle Install Status</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">Are you sure you want to toggle the install status of this server?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Confirm Toggle</button>
                        <button @click="toggleModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div x-show="suspendModal.open" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="suspendUrl" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="action" :value="suspendModal.action" />
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa fa-exclamation-circle text-yellow-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-white" x-text="suspendModal.action == 'suspend' ? 'Suspend Server' : 'Unsuspend Server'"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400" x-text="suspendModal.action == 'suspend' ? 'Are you sure you want to suspend this server? This will stop all running processes and block user access.' : 'Are you sure you want to unsuspend this server? This will restore normal user access.'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" x-text="suspendModal.action == 'suspend' ? 'Suspend' : 'Unsuspend'"></button>
                        <button @click="suspendModal.open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transfer Modal -->
    <div x-show="transferModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-gray-900 opacity-75"></div></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="transferUrl" method="POST">
                    {!! csrf_field() !!}
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-white mb-4">Transfer Server</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="pNodeId" class="block text-sm font-medium text-gray-300">Node</label>
                                <select name="node_id" id="pNodeId" x-model="selectedNode" @change="updateAllocations()" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white">
                                    @foreach($locations as $location)
                                        <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                            @foreach($location->nodes as $node)
                                                @if($node->id != $server->node_id)
                                                    <option value="{{ $node->id }}">{{ $node->name }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="pAllocation" class="block text-sm font-medium text-gray-300">Default Allocation</label>
                                <select name="allocation_id" id="pAllocation" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white">
                                    <template x-for="allocation in allocationsForSelectedNode" :key="allocation.id">
                                        <option :value="allocation.id" x-text="`${allocation.ip}:${allocation.port}`"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label for="pAllocationAdditional" class="block text-sm font-medium text-gray-300">Additional Allocations</label>
                                <select name="allocation_additional[]" id="pAllocationAdditional" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white" multiple>
                                    <template x-for="allocation in allocationsForSelectedNode" :key="allocation.id">
                                        <option :value="allocation.id" x-text="`${allocation.ip}:${allocation.port}`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Confirm Transfer</button>
                        <button @click="transferModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function manageServer() {
            return {
                reinstallModal: false,
                toggleModal: false,
                suspendModal: { open: false, action: '' },
                transferModal: false,
                reinstallUrl: '{{ route("admin.servers.view.manage.reinstall", $server->id) }}',
                toggleUrl: '{{ route("admin.servers.view.manage.toggle", $server->id) }}',
                suspendUrl: '{{ route("admin.servers.view.manage.suspension", $server->id) }}',
                transferUrl: '{{ route("admin.servers.view.manage.transfer", $server->id) }}',
                
                // Data for transfer
                locations: {!! json_encode($locations->map->nodes->keyBy('id')) !!},
                allAllocations: {!! json_encode($locations->flatMap->nodes->flatMap->allocations->groupBy('node_id')) !!},
                selectedNode: '{{ $locations->flatMap->nodes->where('id', '!=', $server->node_id)->first()->id ?? '' }}',
                allocationsForSelectedNode: [],

                openReinstallModal() { this.reinstallModal = true; },
                openToggleModal() { this.toggleModal = true; },
                openSuspendModal(action) { 
                    this.suspendModal.action = action;
                    this.suspendModal.open = true; 
                },
                openTransferModal() { 
                    this.transferModal = true; 
                    this.updateAllocations();
                },

                updateAllocations() {
                    this.allocationsForSelectedNode = this.allAllocations[this.selectedNode] || [];
                },

                init() {
                    this.updateAllocations();
                }
            }
        }
    </script>
@endsection
