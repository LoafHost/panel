@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Allocations
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">{{ $node->name }}<small class="text-gray-400"> :: Allocations</small></h1>
    <p class="text-sm text-gray-400">Control allocations available for servers on this node.</p>
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
        <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="text-white font-medium">Allocation</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="hover:text-white">Servers</a>
    </div>

    <div x-data="{
        deleteIpModal: false,
        massDeleteModal: false,
        selected: [],
        toggle(id) {
            if (this.selected.includes(id)) {
                this.selected = this.selected.filter(i => i !== id);
            } else {
                this.selected.push(id);
            }
        },
        toggleAll(event) {
            const checkboxes = document.querySelectorAll('.allocation-checkbox');
            if (event.target.checked) {
                this.selected = [...checkboxes].map(cb => cb.value);
            } else {
                this.selected = [];
            }
        }
    }">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Existing Allocations -->
            <div class="lg:col-span-2 bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-white">Existing Allocations</h3>
                    <div>
                        <button @click="deleteIpModal = true" class="text-sm bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">Delete by IP</button>
                        <button @click="massDeleteModal = true" x-show="selected.length > 0" class="ml-2 text-sm bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">Delete Selected</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"><input type="checkbox" @change="toggleAll($event)" class="form-checkbox h-4 w-4 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500"></th>
                                <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">IP Address</th>
                                <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">IP Alias</th>
                                <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Port</th>
                                <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Assigned To</th>
                                <th scope="col" class="relative p-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($allocations as $allocation)
                                <tr x-data="{ alias: '{{ $allocation->ip_alias }}', feedback: '' }" data-allocation-id="{{ $allocation->id }}">
                                    <td class="p-3">
                                        @if(is_null($allocation->server_id))
                                            <input type="checkbox" class="allocation-checkbox form-checkbox h-4 w-4 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500" value="{{ $allocation->id }}" :checked="selected.includes({{ $allocation->id }})" @change="toggle({{ $allocation->id }})">
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-sm text-gray-300">{{ $allocation->ip }}</td>
                                    <td class="p-3">
                                        <input class="block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white sm:text-sm p-2" type="text" x-model="alias" @keyup.debounce.500ms="
                                            fetch('/admin/nodes/view/{{ $node->id }}/allocation/alias', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                                body: JSON.stringify({ alias: alias, allocation_id: {{ $allocation->id }} })
                                            }).then(res => {
                                                feedback = res.ok ? 'text-green-500' : 'text-red-500';
                                                setTimeout(() => feedback = '', 2000);
                                            }).catch(() => { feedback = 'text-red-500'; setTimeout(() => feedback = '', 2000); });
                                        " :class="feedback" placeholder="none" />
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-sm text-gray-300">{{ $allocation->port }}</td>
                                    <td class="p-3 whitespace-nowrap text-sm">
                                        @if(! is_null($allocation->server))
                                            <a href="{{ route('admin.servers.view', $allocation->server_id) }}" class="text-indigo-400 hover:text-indigo-300">{{ $allocation->server->name }}</a>
                                        @else
                                            <span class="text-gray-400">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="p-3 whitespace-nowrap text-right text-sm font-medium">
                                        @if(is_null($allocation->server_id))
                                            <button @click="
                                                if(confirm('Are you sure you want to delete this allocation?')) {
                                                    fetch('/admin/nodes/view/{{ $node->id }}/allocation/remove/{{ $allocation->id }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                                                    .then(res => res.ok ? $el.closest('tr').remove() : alert('Failed to delete allocation.'))
                                                }
                                            " class="text-red-500 hover:text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($allocations->hasPages())
                    <div class="mt-4 bg-gray-800 px-4 py-3 flex items-center justify-between sm:px-6">
                        {{ $allocations->render() }}
                    </div>
                @endif
            </div>

            <!-- Assign New Allocations -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md h-fit">
                <h3 class="text-lg font-semibold text-white mb-4">Assign New Allocations</h3>
                <form action="{{ route('admin.nodes.view.allocation', $node->id) }}" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="allocation_ip" class="block text-sm font-medium text-gray-300">IP Address</label>
                            <input type="text" name="allocation_ip" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. 127.0.0.1">
                            <p class="mt-1 text-xs text-gray-400">Enter an IP address to assign ports to.</p>
                        </div>
                        <div>
                            <label for="allocation_alias" class="block text-sm font-medium text-gray-300">IP Alias</label>
                            <input type="text" name="allocation_alias" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="optional-alias">
                            <p class="mt-1 text-xs text-gray-400">Assign a default alias to these allocations.</p>
                        </div>
                        <div>
                            <label for="allocation_ports" class="block text-sm font-medium text-gray-300">Ports</label>
                            <input type="text" name="allocation_ports" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. 25565, 25566-25570">
                            <p class="mt-1 text-xs text-gray-400">Enter individual ports or ranges separated by commas.</p>
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        {!! csrf_field() !!}
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete by IP Modal -->
        <div x-show="deleteIpModal" x-cloak class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="deleteIpModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="deleteIpModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('admin.nodes.view.allocation.removeBlock', $node->id) }}" method="POST">
                        <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-semibold text-white mb-4">Delete Allocations by IP</h3>
                            <select name="ip" class="block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($allocations->unique('ip') as $allocation)
                                    <option value="{{ $allocation->ip }}">{{ $allocation->ip }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            {!! csrf_field() !!}
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                            <button @click="deleteIpModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mass Delete Modal -->
        <div x-show="massDeleteModal" x-cloak class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="massDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="massDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-white mb-4">Delete Selected Allocations</h3>
                        <p class="text-sm text-gray-400">Are you sure you want to delete the <span x-text="selected.length"></span> selected allocations? This action cannot be undone.</p>
                    </div>
                    <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="
                            fetch('/admin/nodes/view/{{ $node->id }}/allocation/removeMultiple', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                body: JSON.stringify({ allocations: selected })
                            }).then(res => res.ok ? window.location.reload() : alert('Failed to delete selected allocations.'));
                            massDeleteModal = false;
                        " class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                        <button @click="massDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
