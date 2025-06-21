@extends('layouts.admin')

@section('title', 'Nests → ' . $nest->name)

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $nest->name }}</h1>
    <p class="text-gray-400">{{ $nest->description }}</p>
@endsection

@section('content')
<div x-data="{ deleteModal: false }">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Left Column: Nest Details Form -->
        <div>
            <form action="{{ route('admin.nests.view', $nest->id) }}" method="POST">
                <div class="bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="{{ $nest->name }}" />
                        <p class="text-sm text-gray-400 mt-2">A descriptive category name that encompasses all options within the service.</p>
                    </div>
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea id="description" name="description" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" rows="7">{{ $nest->description }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="PATCH">
                        <button type="button" @click="deleteModal = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2"><i class="fas fa-trash-alt"></i></button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column: Nest Info -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Nest ID</label>
                <input type="text" readonly class="block w-full px-4 py-2 bg-gray-900 border-gray-700 rounded-md text-white cursor-not-allowed" value="{{ $nest->id }}" />
                <p class="text-sm text-gray-400 mt-2">A unique ID for this nest, used for internal identification and API access.</p>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Author</label>
                <input type="text" readonly class="block w-full px-4 py-2 bg-gray-900 border-gray-700 rounded-md text-white cursor-not-allowed" value="{{ $nest->author }}" />
                <p class="text-sm text-gray-400 mt-2">For issues, contact the author unless it's an official Loaf Panel nest.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">UUID</label>
                <input type="text" readonly class="block w-full px-4 py-2 bg-gray-900 border-gray-700 rounded-md text-white cursor-not-allowed" value="{{ $nest->uuid }}" />
                <p class="text-sm text-gray-400 mt-2">A unique identifier for all servers using this nest.</p>
            </div>
        </div>
    </div>

    <!-- Eggs Table -->
    <div class="bg-gray-800 shadow-md rounded-lg mt-8">
        <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="text-lg text-white font-semibold">Nest Eggs</h3>
            <a href="{{ route('admin.eggs.new') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">New Egg</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-white">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Servers</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse($nest->eggs as $egg)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $egg->id }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.nests.egg.view', $egg->id) }}" class="text-blue-400 hover:text-blue-300">{{ $egg->name }}</a></td>
                            <td class="px-6 py-4 text-gray-300">{{ $egg->description }}</td>
                            <td class="px-6 py-4 text-center text-gray-300">{{ $egg->servers_count }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.nests.egg.export', ['egg' => $egg->id]) }}" class="text-gray-400 hover:text-white"><i class="fas fa-download"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">No eggs found for this nest.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Nest Modal -->
    <div x-show="deleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="deleteModal" @click="deleteModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="deleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white">Delete Nest</h3>
                    <button @click="deleteModal = false" class="text-gray-400 hover:text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <p class="mt-4 text-gray-300">Are you sure you want to delete this nest? All eggs and servers attached to it will be removed. This is a permanent action.</p>
                <div class="mt-6 flex justify-end">
                    <form action="{{ route('admin.nests.view', $nest->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" @click="deleteModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">Delete Nest</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    {{-- Old jQuery script removed, replaced by Alpine.js modal --}}
@endsection
