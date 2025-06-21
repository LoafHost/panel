@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Settings
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">{{ $node->name }}<small class="text-gray-400"> :: Settings</small></h1>
    <p class="text-sm text-gray-400">Configure your node settings.</p>
@endsection

@section('content')
<div class="mt-8">
    <div class="flex items-center mb-4 text-sm text-gray-400">
        <a href="{{ route('admin.nodes.view', $node->id) }}" class="hover:text-white">About</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="text-white font-medium">Settings</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="hover:text-white">Configuration</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="hover:text-white">Allocation</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="hover:text-white">Servers</a>
    </div>

    <div x-data="{ open: false }">
        <form action="{{ route('admin.nodes.view.settings', $node->id) }}" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Settings -->
                <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-white mb-4">Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">Node Name</label>
                            <input type="text" autocomplete="off" name="name" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('name', $node->name) }}">
                            <p class="mt-1 text-xs text-gray-400">Character limits: <code>a-zA-Z0-9_.-</code> and <code>[Space]</code> (min 1, max 100 characters).</p>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">{{ $node->description }}</textarea>
                        </div>
                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-300">Location</label>
                            <select name="location_id" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (old('location_id', $node->location_id) === $location->id) ? 'selected' : '' }}>{{ $location->long }} ({{ $location->short }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Allow Automatic Allocation</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="public" value="1" @if(old('public', $node->public)) checked @endif id="public_1" class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Yes</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="public" value="0" @if(!old('public', $node->public)) checked @endif id="public_0" class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">No</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="fqdn" class="block text-sm font-medium text-gray-300">Fully Qualified Domain Name</label>
                            <input type="text" autocomplete="off" name="fqdn" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('fqdn', $node->fqdn) }}">
                            <p class="mt-1 text-xs text-gray-400">Enter domain name (e.g. <code>node.example.com</code>). An IP address may only be used if you are not using SSL for this node.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Communicate Over SSL</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" id="pSSLTrue" value="https" name="scheme" @if(old('scheme', $node->scheme) === 'https') checked @endif class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Use SSL Connection</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(old('scheme', $node->scheme) !== 'https') checked @endif class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Use HTTP Connection</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">In most cases you should select to use a SSL connection.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Behind Proxy</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" @if(old('behind_proxy', $node->behind_proxy) == false) checked @endif class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Not Behind Proxy</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" id="pProxyTrue" value="1" name="behind_proxy" @if(old('behind_proxy', $node->behind_proxy) == true) checked @endif class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Behind Proxy</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">If you are running the daemon behind a proxy such as Cloudflare, select this.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Maintenance Mode</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode" @if(old('maintenance_mode', $node->maintenance_mode) == false) checked @endif class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Disabled</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode" @if(old('maintenance_mode', $node->maintenance_mode) == true) checked @endif class="form-radio text-indigo-600 bg-gray-700 border-gray-600">
                                    <span class="ml-2 text-gray-300">Enabled</span>
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">If enabled, users won't be able to access servers on this node.</p>
                        </div>
                    </div>
                </div>

                <!-- Allocation & General Config -->
                <div class="space-y-8">
                    <!-- Allocation Limits -->
                    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-white mb-4">Allocation Limits</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="memory" class="block text-sm font-medium text-gray-300">Total Memory</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="memory" class="flex-1 block w-full min-w-0 bg-gray-700 border-gray-600 rounded-none rounded-l-md text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('memory', $node->memory) }}">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-600 bg-gray-600 text-gray-300 text-sm">MiB</span>
                                </div>
                            </div>
                            <div>
                                <label for="memory_overallocate" class="block text-sm font-medium text-gray-300">Overallocate</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="memory_overallocate" class="flex-1 block w-full min-w-0 bg-gray-700 border-gray-600 rounded-none rounded-l-md text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('memory_overallocate', $node->memory_overallocate) }}">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-600 bg-gray-600 text-gray-300 text-sm">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">Enter the total memory and optional over-allocation percentage.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="disk" class="block text-sm font-medium text-gray-300">Disk Space</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="disk" class="flex-1 block w-full min-w-0 bg-gray-700 border-gray-600 rounded-none rounded-l-md text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('disk', $node->disk) }}">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-600 bg-gray-600 text-gray-300 text-sm">MiB</span>
                                </div>
                            </div>
                            <div>
                                <label for="disk_overallocate" class="block text-sm font-medium text-gray-300">Overallocate</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="disk_overallocate" class="flex-1 block w-full min-w-0 bg-gray-700 border-gray-600 rounded-none rounded-l-md text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('disk_overallocate', $node->disk_overallocate) }}">
                                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-600 bg-gray-600 text-gray-300 text-sm">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">Enter the total disk space and optional over-allocation percentage.</p>
                    </div>

                    <!-- General Configuration -->
                    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-white mb-4">General Configuration</h3>
                        <div>
                            <label for="upload_size" class="block text-sm font-medium text-gray-300">Maximum Web Upload Filesize</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" name="upload_size" class="flex-1 block w-full min-w-0 bg-gray-700 border-gray-600 rounded-none rounded-l-md text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('upload_size', $node->upload_size) }}">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-600 bg-gray-600 text-gray-300 text-sm">MiB</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Enter the maximum size of files that can be uploaded through the file manager.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="daemonListen" class="block text-sm font-medium text-gray-300">Daemon Port</label>
                                <input type="text" name="daemonListen" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('daemonListen', $node->daemonListen) }}">
                            </div>
                            <div>
                                <label for="daemonSFTP" class="block text-sm font-medium text-gray-300">Daemon SFTP Port</label>
                                <input type="text" name="daemonSFTP" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('daemonSFTP', $node->daemonSFTP) }}">
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-400">The daemon runs its own SFTP management container. Do not use the same port as your server's SSH process.</p>
                    </div>
                </div>
            </div>

            <!-- Save Settings -->
            <div class="mt-8 bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-white mb-4">Save Settings</h3>
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="reset_secret" id="reset_secret" class="form-checkbox h-4 w-4 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="reset_secret" class="font-medium text-gray-300">Reset Daemon Master Key</label>
                        <p class="text-gray-400 text-xs">Resetting the daemon master key will void any request from the old key. This key is used for all sensitive operations on the daemon.</p>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    {!! method_field('PATCH') !!}
                    {!! csrf_field() !!}
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-8 bg-red-900 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-white mb-4">Delete Node</h3>
            <p class="text-sm text-gray-300 mb-4">
                Deleting a node is an irreversible action. All servers attached to this node will be deleted.
            </p>
            <button @click="open = true" type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">
                Delete Node
            </button>
        </div>

        <!-- Delete Node Modal -->
        <div x-show="open" x-cloak class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form id="delete-node-form" action="{{ route('admin.nodes.view.delete', $node->id) }}" method="POST">
                        <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                        Delete Node
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-400">
                                            Are you sure you want to delete this node? All servers attached to this node will also be deleted. This action cannot be undone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            {!! csrf_field() !!}
                            {!! method_field('DELETE') !!}
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                            <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    {{-- The original script for popover and select2 is not needed for the new design. --}}
@endsection
