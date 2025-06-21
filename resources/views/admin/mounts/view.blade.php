@extends('layouts.admin')

@section('title', 'Mounts → ' . $mount->name)

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $mount->name }}</h1>
    <p class="text-gray-400">{{ $mount->description }}</p>
@endsection

@section('content')
<div x-data="{
    deleteModal: false,
    addEggsModal: false,
    addNodesModal: false,
    detachConfirmationModal: false,
    detachType: '',
    detachId: null,
    detachName: '',
    detachItem() {
        fetch(`/admin/mounts/{{ $mount->id }}/${this.detachType}/${this.detachId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                const row = document.querySelector(`tr[data-row-id='${this.detachType}-${this.detachId}']`);
                if (row) {
                    row.style.backgroundColor = '#3B0713';
                    setTimeout(() => row.remove(), 300);
                }
                this.detachConfirmationModal = false;
            } else {
                response.json().then(data => {
                    console.error(data);
                    // TODO: Show an error notification to the user.
                }).catch(() => {
                    console.error('An error occurred while detaching the item.');
                });
                this.detachConfirmationModal = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.detachConfirmationModal = false;
        });
    }
}">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Mount Details -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Mount Details</h3>
            <form action="{{ route('admin.mounts.view', $mount->id) }}" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300">Unique ID</label>
                    <input type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-400 rounded-md shadow-sm" value="{{ $mount->uuid }}" readonly />
                </div>
                <div class="mb-4">
                    <label for="pName" class="block text-sm font-medium text-gray-300">Name</label>
                    <input type="text" id="pName" name="name" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ $mount->name }}" />
                </div>
                <div class="mb-4">
                    <label for="pDescription" class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea id="pDescription" name="description" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="4">{{ $mount->description }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label for="pSource" class="block text-sm font-medium text-gray-300">Source</label>
                        <input type="text" id="pSource" name="source" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ $mount->source }}" />
                    </div>
                    <div>
                        <label for="pTarget" class="block text-sm font-medium text-gray-300">Target</label>
                        <input type="text" id="pTarget" name="target" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ $mount->target }}" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Read Only</label>
                        <div class="flex items-center mt-2 space-x-4">
                            <label class="flex items-center"><input type="radio" name="read_only" value="0" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if(!$mount->read_only) checked @endif><span class="ml-2 text-white">False</span></label>
                            <label class="flex items-center"><input type="radio" name="read_only" value="1" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if($mount->read_only) checked @endif><span class="ml-2 text-white">True</span></label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">User Mountable</label>
                        <div class="flex items-center mt-2 space-x-4">
                            <label class="flex items-center"><input type="radio" name="user_mountable" value="0" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if(!$mount->user_mountable) checked @endif><span class="ml-2 text-white">False</span></label>
                            <label class="flex items-center"><input type="radio" name="user_mountable" value="1" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if($mount->user_mountable) checked @endif><span class="ml-2 text-white">True</span></label>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="deleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <div>
                        {!! csrf_field() !!}
                        {!! method_field('PATCH') !!}
                        <button type="submit" name="action" value="edit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="space-y-8">
            <!-- Eggs -->
            <div class="bg-gray-800 shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl text-white font-semibold">Eggs</h3>
                    <button @click="addEggsModal = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add Eggs</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-900 rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach ($mount->eggs as $egg)
                                <tr data-row-id="egg-{{ $egg->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $egg->id }}</code></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.nests.egg.view', $egg->id) }}" class="text-blue-400 hover:text-blue-500">{{ $egg->name }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button @click="detachType = 'egg'; detachId = {{ $egg->id }}; detachName = '{{ $egg->name }}'; detachConfirmationModal = true" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Nodes -->
            <div class="bg-gray-800 shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl text-white font-semibold">Nodes</h3>
                    <button @click="addNodesModal = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add Nodes</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-900 rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">FQDN</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach ($mount->nodes as $node)
                                <tr data-row-id="node-{{ $node->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $node->id }}</code></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.nodes.view', $node->id) }}" class="text-blue-400 hover:text-blue-500">{{ $node->name }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $node->fqdn }}</code></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button @click="detachType = 'node'; detachId = {{ $node->id }}; detachName = '{{ $node->name }}'; detachConfirmationModal = true" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="deleteModal" @click="deleteModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="deleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white">Delete Mount</h3>
                    <button @click="deleteModal = false" class="text-gray-400 hover:text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <p class="mt-4 text-gray-300">Are you sure you want to delete this mount? This action cannot be undone.</p>
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('admin.mounts.view', $mount->id) }}" method="POST">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button type="button" @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" name="action" value="delete" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Detach Confirmation Modal -->
    <div x-show="detachConfirmationModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="detachConfirmationModal" @click="detachConfirmationModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="detachConfirmationModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white">Detach <span x-text="detachType.charAt(0).toUpperCase() + detachType.slice(1)"></span></h3>
                    <button @click="detachConfirmationModal = false" class="text-gray-400 hover:text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <p class="mt-4 text-gray-300">Are you sure you want to detach <strong x-text="detachName"></strong> from this mount?</p>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="detachConfirmationModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                    <button type="button" @click="detachItem()" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">Detach</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Eggs Modal -->
    <div x-show="addEggsModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="addEggsModal" @click="addEggsModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="addEggsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white">Add Eggs</h3>
                    <button @click="addEggsModal = false" class="text-gray-400 hover:text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <form action="{{ route('admin.mounts.eggs', $mount->id) }}" method="POST" class="mt-6">
                    {!! csrf_field() !!}
                    <label for="pEggs" class="block text-sm font-medium text-gray-300">Eggs</label>
                    <select id="pEggs" name="eggs[]" class="block w-full mt-1 bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" multiple>
                        @foreach ($nests as $nest)
                            <optgroup label="{{ $nest->name }}">
                                @foreach ($nest->eggs as $egg)
                                    @if (! in_array($egg->id, $mount->eggs->pluck('id')->toArray()))
                                        <option value="{{ $egg->id }}">{{ $egg->name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="addEggsModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Nodes Modal -->
    <div x-show="addNodesModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="addNodesModal" @click="addNodesModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="addNodesModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white">Add Nodes</h3>
                    <button @click="addNodesModal = false" class="text-gray-400 hover:text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <form action="{{ route('admin.mounts.nodes', $mount->id) }}" method="POST" class="mt-6">
                    {!! csrf_field() !!}
                    <label for="pNodes" class="block text-sm font-medium text-gray-300">Nodes</label>
                    <select id="pNodes" name="nodes[]" class="block w-full mt-1 bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" multiple>
                        @foreach ($locations as $location)
                            <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                @foreach ($location->nodes as $node)
                                    @if (! in_array($node->id, $mount->nodes->pluck('id')->toArray()))
                                        <option value="{{ $node->id }}">{{ $node->name }}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="addNodesModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    {{-- The old jQuery-based script for detaching has been replaced with an Alpine.js component. --}}
    {{-- The select2 library has been removed in favor of native browser select styling. --}}
@endsection
