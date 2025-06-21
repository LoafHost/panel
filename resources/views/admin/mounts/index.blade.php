@extends('layouts.admin')

@section('title', 'Mounts')

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Mounts</h1>
    <p class="text-gray-400">Configure and manage additional mount points for servers.</p>
@endsection

@section('content')
<div x-data="{ newMountModal: false }">
    <div class="bg-gray-800 shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl text-white font-semibold">Mount List</h2>
            <button @click="newMountModal = true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Create New
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-900 rounded-lg">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Eggs</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Nodes</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Servers</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse ($mounts as $mount)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $mount->id }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.mounts.view', $mount->id) }}" class="text-blue-400 hover:text-blue-500">{{ $mount->name }}</a></td>
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $mount->source }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $mount->target }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $mount->eggs_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $mount->nodes_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $mount->servers_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">No mounts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create New Mount Modal -->
    <div x-show="newMountModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="newMountModal" @click="newMountModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="newMountModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white" id="modal-title">Create Mount</h3>
                    <button @click="newMountModal = false" class="text-gray-400 hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.mounts') }}" method="POST" class="mt-6">
                    {!! csrf_field() !!}
                    <div class="mb-4">
                        <label for="pName" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" id="pName" name="name" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                        <p class="mt-2 text-sm text-gray-400">Unique name to identify this mount.</p>
                    </div>
                    <div class="mb-4">
                        <label for="pDescription" class="block text-sm font-medium text-gray-300">Description</label>
                        <textarea id="pDescription" name="description" rows="4" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        <p class="mt-2 text-sm text-gray-400">A longer description for this mount (max 191 characters).</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label for="pSource" class="block text-sm font-medium text-gray-300">Source</label>
                            <input type="text" id="pSource" name="source" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                            <p class="mt-2 text-sm text-gray-400">File path on the host system to mount.</p>
                        </div>
                        <div>
                            <label for="pTarget" class="block text-sm font-medium text-gray-300">Target</label>
                            <input type="text" id="pTarget" name="target" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                            <p class="mt-2 text-sm text-gray-400">Mount path inside the container.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Read Only</label>
                            <div class="flex items-center mt-2 space-x-4">
                                <label class="flex items-center"><input type="radio" name="read_only" value="0" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" checked><span class="ml-2 text-white">False</span></label>
                                <label class="flex items-center"><input type="radio" name="read_only" value="1" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500"><span class="ml-2 text-white">True</span></label>
                            </div>
                            <p class="mt-2 text-sm text-gray-400">Is the mount read-only inside the container?</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">User Mountable</label>
                            <div class="flex items-center mt-2 space-x-4">
                                <label class="flex items-center"><input type="radio" name="user_mountable" value="0" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" checked><span class="ml-2 text-white">False</span></label>
                                <label class="flex items-center"><input type="radio" name="user_mountable" value="1" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500"><span class="ml-2 text-white">True</span></label>
                            </div>
                            <p class="mt-2 text-sm text-gray-400">Can users mount this themselves?</p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="newMountModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
