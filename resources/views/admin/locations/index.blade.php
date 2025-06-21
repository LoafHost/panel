@extends('layouts.admin')

@section('title')
    Locations
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Locations</h1>
    <p class="text-gray-400">All locations that nodes can be assigned to for easier categorization.</p>
@endsection

@section('content')
<div x-data="{ newLocationModal: false }" class="bg-gray-800 shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl text-white font-semibold">Location List</h2>
        <button @click="newLocationModal = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Create New
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-900 rounded-lg">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Short Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Nodes</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Servers</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @foreach ($locations as $location)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $location->id }}</code></td>
                        <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.locations.view', $location->id) }}" class="text-blue-400 hover:text-blue-500">{{ $location->short }}</a></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $location->long }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $location->nodes_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $location->servers_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create New Location Modal -->
<div x-show="newLocationModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="newLocationModal" @click="newLocationModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="newLocationModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
            <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                <h3 class="text-lg font-medium leading-6 text-white" id="modal-title">Create New Location</h3>
                <button @click="newLocationModal = false" class="text-gray-400 hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.locations') }}" method="POST" class="mt-6">
                {!! csrf_field() !!}
                <div>
                    <label for="pShortModal" class="block text-sm font-medium text-gray-300">Short Code</label>
                    <input type="text" name="short" id="pShortModal" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                    <p class="mt-2 text-sm text-gray-400">A short identifier (e.g., <code>us.nyc.lvl3</code>). Must be 1-60 characters.</p>
                </div>
                <div class="mt-4">
                    <label for="pLongModal" class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea name="long" id="pLongModal" rows="4" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    <p class="mt-2 text-sm text-gray-400">A longer description of this location. Must be less than 191 characters.</p>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" @click="newLocationModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
