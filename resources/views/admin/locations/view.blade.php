@extends('layouts.admin')

@section('title', 'Locations → ' . $location->short)

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $location->short }}</h1>
    <p class="text-gray-400">{{ $location->long }}</p>
@endsection

@section('content')
<div x-data="{ deleteModal: false }">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Location Details -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Location Details</h3>
            <form action="{{ route('admin.locations.view', $location->id) }}" method="POST">
                <div class="mb-4">
                    <label for="pShort" class="block text-sm font-medium text-gray-300">Short Code</label>
                    <input type="text" id="pShort" name="short" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ $location->short }}" />
                </div>
                <div class="mb-4">
                    <label for="pLong" class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea id="pLong" name="long" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="4">{{ $location->long }}</textarea>
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

        <!-- Nodes Table -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Nodes</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-900 rounded-lg">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">FQDN</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Servers</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @forelse($location->nodes as $node)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $node->id }}</code></td>
                                <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.nodes.view', $node->id) }}" class="text-blue-400 hover:text-blue-500">{{ $node->name }}</a></td>
                                <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $node->fqdn }}</code></td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $node->servers_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-400">No nodes found for this location.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                    <h3 class="text-lg font-medium leading-6 text-white" id="modal-title">Delete Location</h3>
                    <button @click="deleteModal = false" class="text-gray-400 hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-300">Are you sure you want to delete this location? This will remove all nodes and servers associated with it. This action cannot be undone.</p>
                </div>
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('admin.locations.view', $location->id) }}" method="POST">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button type="button" @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
