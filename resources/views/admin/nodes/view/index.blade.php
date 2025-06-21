@extends('layouts.modern')

@section('title')
    {{ $node->name }}
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $node->name }}<small class="text-gray-400"> A quick overview of your node.</small></h1>
@endsection

@section('content')
<div class="grid grid-cols-12 gap-4">
    <div class="col-span-12">
        <div class="bg-gray-800 rounded-lg shadow-lg">
            <div class="px-4 sm:px-0">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select id="tabs" name="tabs" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-700 bg-gray-900 text-white rounded-md">
                        <option selected>About</option>
                        <option>Settings</option>
                        <option>Configuration</option>
                        <option>Allocation</option>
                        <option>Servers</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <div class="border-b border-gray-700">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                            <a href="{{ route('admin.nodes.view', $node->id) }}" class="border-indigo-500 text-indigo-400 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">About</a>
                            <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Settings</a>
                            <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Configuration</a>
                            <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Allocation</a>
                            <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="border-transparent text-gray-400 hover:text-gray-200 hover:border-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Servers</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2 space-y-8">
        <div class="bg-gray-800 rounded-lg shadow-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium leading-6 text-white">Information</h3>
            </div>
            <div class="border-t border-gray-700 px-6 py-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-400">Daemon Version</dt>
                        <dd class="mt-1 text-sm text-white"><code data-attr="info-version"><i class="fas fa-sync-alt fa-spin"></i></code> (Latest: <code>{{ $version->getDaemon() }}</code>)</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-400">System Information</dt>
                        <dd class="mt-1 text-sm text-white" data-attr="info-system"><i class="fas fa-sync-alt fa-spin"></i></dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-400">Total CPU Threads</dt>
                        <dd class="mt-1 text-sm text-white" data-attr="info-cpus"><i class="fas fa-sync-alt fa-spin"></i></dd>
                    </div>
                </dl>
            </div>
        </div>
        @if ($node->description)
            <div class="bg-gray-800 rounded-lg shadow-lg">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium leading-6 text-white">Description</h3>
                </div>
                <div class="border-t border-gray-700 px-6 py-4">
                    <p class="text-sm text-gray-300">{{ $node->description }}</p>
                </div>
            </div>
        @endif
        <div class="bg-gray-800 rounded-lg shadow-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium leading-6 text-white">Delete Node</h3>
            </div>
            <div class="border-t border-gray-700 px-6 py-4">
                <p class="text-sm text-gray-400">Deleting a node is an irreversible action. There must be no servers associated with this node to continue.</p>
                <div class="mt-4">
                    <form action="{{ route('admin.nodes.view.delete', $node->id) }}" method="POST">
                        {!! csrf_field() !!}
                        {!! method_field('DELETE') !!}
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" {{ ($node->servers_count < 1) ?: 'disabled' }}>Delete Node</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-8">
        <div class="bg-gray-800 rounded-lg shadow-lg">
            <div class="px-6 py-4">
                <h3 class="text-lg font-medium leading-6 text-white">At-a-Glance</h3>
            </div>
            <div class="border-t border-gray-700 px-6 py-4 space-y-6">
                @if($node->maintenance_mode)
                <div class="flex items-center p-4 bg-yellow-500 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="fas fa-wrench text-white"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">This node is under Maintenance</p>
                    </div>
                </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-gray-400">Disk Space Allocated</p>
                    <p class="text-sm text-white">{{ $stats['disk']['value'] }} / {{ $stats['disk']['max'] }} MiB</p>
                    <div class="mt-2 bg-gray-700 rounded-full h-2.5">
                        <div class="bg-{{ $stats['disk']['css'] }}-600 h-2.5 rounded-full" style="width: {{ $stats['disk']['percent'] }}%"></div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Memory Allocated</p>
                    <p class="text-sm text-white">{{ $stats['memory']['value'] }} / {{ $stats['memory']['max'] }} MiB</p>
                    <div class="mt-2 bg-gray-700 rounded-full h-2.5">
                        <div class="bg-{{ $stats['memory']['css'] }}-600 h-2.5 rounded-full" style="width: {{ $stats['memory']['percent'] }}%"></div>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-400">Total Servers</p>
                    <p class="text-2xl font-bold text-white">{{ $node->servers_count }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    (function getInformation() {
        $.ajax({
            method: 'GET',
            url: '/admin/nodes/view/{{ $node->id }}/system-information',
            timeout: 5000,
        }).done(function (data) {
            $('[data-attr="info-version"]').html(escapeHtml(data.version));
            $('[data-attr="info-system"]').html(escapeHtml(data.system.type) + ' (' + escapeHtml(data.system.arch) + ') <code>' + escapeHtml(data.system.release) + '</code>');
            $('[data-attr="info-cpus"]').html(data.system.cpus);
        }).fail(function (jqXHR) {
            console.error(jqXHR);
        }).always(function() {
            setTimeout(getInformation, 10000);
        });
    })();
    </script>
@endsection
