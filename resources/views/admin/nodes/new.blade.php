@extends('layouts.admin')

@section('title', 'Nodes → New')

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Create New Node</h1>
    <p class="text-gray-400">Create a new local or remote node for server deployment.</p>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Basic Details -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-6">Basic Details</h3>
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                    <input type="text" id="name" name="name" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="{{ old('name') }}"/>
                    <p class="mt-2 text-xs text-gray-400">Character limits: <code>a-zA-Z0-9_.-</code> and <code>[Space]</code> (min 1, max 100 characters).</p>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                    <select id="location_id" name="location_id" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500">
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->short }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Node Visibility</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center"><input type="radio" name="public" value="1" class="h-4 w-4 text-blue-600 bg-gray-900 border-gray-700 focus:ring-blue-500" checked><span class="ml-2 text-white">Public</span></label>
                        <label class="flex items-center"><input type="radio" name="public" value="0" class="h-4 w-4 text-blue-600 bg-gray-900 border-gray-700 focus:ring-blue-500"><span class="ml-2 text-white">Private</span></label>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">By setting a node to <code>private</code>, you deny auto-deployment to this node.</p>
                </div>
                <div>
                    <label for="fqdn" class="block text-sm font-medium text-gray-300 mb-2">FQDN</label>
                    <input type="text" id="fqdn" name="fqdn" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="{{ old('fqdn') }}"/>
                    <p class="mt-2 text-xs text-gray-400">Enter the domain name (e.g., <code>node.example.com</code>) for connecting to the daemon. An IP address can be used if not using SSL.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Communicate Over SSL</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center"><input type="radio" name="scheme" value="https" class="h-4 w-4 text-blue-600 bg-gray-900 border-gray-700 focus:ring-blue-500" checked><span class="ml-2 text-white">Use SSL</span></label>
                        <label class="flex items-center"><input type="radio" name="scheme" value="http" class="h-4 w-4 text-blue-600 bg-gray-900 border-gray-700 focus:ring-blue-500" @if(request()->isSecure()) disabled @endif><span class="ml-2 text-white">Use HTTP</span></label>
                    </div>
                    @if(request()->isSecure())
                        <p class="mt-2 text-xs text-red-400">Your Panel is using a secure connection. The node <strong>must</strong> use SSL.</p>
                    @else
                        <p class="mt-2 text-xs text-gray-400">Use SSL in most cases. If using an IP or no SSL, choose HTTP.</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Behind Proxy</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center"><input type="radio" name="behind_proxy" value="0" class="h-4 w-4 text-blue-600 bg-gray-900 border-gray-700 focus:ring-blue-500" checked><span class="ml-2 text-white">Not Behind Proxy</span></label>
                        <label class="flex items-center"><input type="radio" name="behind_proxy" value="1" class="h-4 w-4 text-blue-600 bg-gray-900 border-gray-700 focus:ring-blue-500"><span class="ml-2 text-white">Behind Proxy</span></label>
                    </div>
                    <p class="mt-2 text-xs text-gray-400">If using a proxy like Cloudflare, select this to skip certificate lookups.</p>
                </div>
            </div>
        </div>

        <!-- Configuration -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-6">Configuration</h3>
            <div class="space-y-6">
                <div>
                    <label for="daemonBase" class="block text-sm font-medium text-gray-300 mb-2">Daemon Server File Directory</label>
                    <input type="text" id="daemonBase" name="daemonBase" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="/var/lib/pterodactyl/volumes" />
                    <p class="mt-2 text-xs text-gray-400">Directory for server files. Check partition scheme if using OVH; you might need <code>/home/daemon-data</code>.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="memory" class="block text-sm font-medium text-gray-300 mb-2">Total Memory</label>
                        <div class="relative">
                            <input type="text" id="memory" name="memory" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white pr-12 focus:ring-blue-500 focus:border-blue-500" value="{{ old('memory') }}"/>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">MB</span>
                        </div>
                    </div>
                    <div>
                        <label for="memory_overallocate" class="block text-sm font-medium text-gray-300 mb-2">Memory Over-Allocation</label>
                        <div class="relative">
                            <input type="text" id="memory_overallocate" name="memory_overallocate" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white pr-12 focus:ring-blue-500 focus:border-blue-500" value="{{ old('memory_overallocate', 0) }}"/>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">%</span>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-4">Total memory for new servers. To allow overallocation, enter a percentage. Use <code>-1</code> to disable the limit, <code>0</code> to prevent new servers if over limit.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="disk" class="block text-sm font-medium text-gray-300 mb-2">Total Disk Space</label>
                        <div class="relative">
                            <input type="text" id="disk" name="disk" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white pr-12 focus:ring-blue-500 focus:border-blue-500" value="{{ old('disk') }}"/>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">MB</span>
                        </div>
                    </div>
                    <div>
                        <label for="disk_overallocate" class="block text-sm font-medium text-gray-300 mb-2">Disk Over-Allocation</label>
                        <div class="relative">
                            <input type="text" id="disk_overallocate" name="disk_overallocate" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white pr-12 focus:ring-blue-500 focus:border-blue-500" value="{{ old('disk_overallocate', 0) }}"/>
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">%</span>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-4">Total disk space for new servers. To allow overallocation, enter a percentage. Use <code>-1</code> to disable the limit, <code>0</code> to prevent new servers if over limit.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="daemonListen" class="block text-sm font-medium text-gray-300 mb-2">Daemon Port</label>
                        <input type="text" id="daemonListen" name="daemonListen" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="8080" />
                    </div>
                    <div>
                        <label for="daemonSFTP" class="block text-sm font-medium text-gray-300 mb-2">Daemon SFTP Port</label>
                        <input type="text" id="daemonSFTP" name="daemonSFTP" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="2022" />
                    </div>
                </div>
                <p class="text-xs text-gray-400 -mt-4">The daemon runs its own SFTP container. <strong>Do not use the same port as your server's SSH process.</strong> If behind Cloudflare, set the daemon port to <code>8443</code>.</p>
            </div>
            <div class="mt-8 text-right">
                {!! csrf_field() !!}
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-md">Create Node</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {{-- Select2 has been removed in favor of a standard select input. --}}
@endsection
