@extends('layouts.modern')

@section('title')
    Database Hosts &rarr; View &rarr; {{ $host->name }}
@endsection

@section('content-header')
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <h1 class="text-2xl text-white font-semibold">Database Host: {{ $host->name }}</h1>
            <p class="text-gray-400">Viewing associated databases and details for this database host.</p>
        </div>
        <div class="flex-none">
            <a href="{{ route('admin.databases') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left"></i> Back to Database Hosts
            </a>
        </div>
    </div>
@endsection

@section('content')
<div x-data="{ deleteModal: false }">
    <form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg text-white font-semibold mb-4">Host Details</h3>
                <div class="space-y-4">
                    <div>
                        <label for="pName" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" id="pName" name="name" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('name', $host->name) }}" />
                    </div>
                    <div>
                        <label for="pHost" class="block text-sm font-medium text-gray-300">Host</label>
                        <input type="text" id="pHost" name="host" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('host', $host->host) }}" />
                        <p class="mt-2 text-sm text-gray-400">The IP address or FQDN that should be used when attempting to connect to this MySQL host <em>from the panel</em> to add new databases.</p>
                    </div>
                    <div>
                        <label for="pPort" class="block text-sm font-medium text-gray-300">Port</label>
                        <input type="text" id="pPort" name="port" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('port', $host->port) }}" />
                        <p class="mt-2 text-sm text-gray-400">The port that MySQL is running on for this host.</p>
                    </div>
                    <div>
                        <label for="pNodeId" class="block text-sm font-medium text-gray-300">Linked Node</label>
                        <select name="node_id" id="pNodeId" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">None</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" {{ $host->node_id !== $node->id ?: 'selected' }}>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-gray-400">This setting does nothing other than default to this database host when adding a database to a server on the selected node.</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 p-6 rounded-lg">
                <h3 class="text-lg text-white font-semibold mb-4">User Details</h3>
                <div class="space-y-4">
                    <div>
                        <label for="pUsername" class="block text-sm font-medium text-gray-300">Username</label>
                        <input type="text" name="username" id="pUsername" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('username', $host->username) }}" />
                        <p class="mt-2 text-sm text-gray-400">The username of an account that has enough permissions to create new users and databases on the system.</p>
                    </div>
                    <div>
                        <label for="pPassword" class="block text-sm font-medium text-gray-300">Password</label>
                        <input type="password" name="password" id="pPassword" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                        <p class="mt-2 text-sm text-gray-400">The password to the account defined. Leave blank to continue using the assigned password.</p>
                    </div>
                    <hr class="border-gray-600"/>
                    <p class="text-red-400 text-sm">The account defined for this database host <strong>must</strong> have the <code>WITH GRANT OPTION</code> permission. If the defined account does not have this permission requests to create databases <em>will</em> fail. <strong>Do not use the same account details for MySQL that you have defined for this panel.</strong></p>
                </div>
                <div class="mt-6 flex justify-between">
                    {!! csrf_field() !!}
                    <button type="button" @click="deleteModal = true" class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-trash-o"></i> Delete
                    </button>
                    <button name="_method" value="PATCH" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="deleteModal" @click.away="deleteModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="deleteModal" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                Delete Database Host
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">
                                    Are you sure you want to delete this database host? All databases attached to this host will be removed. This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('admin.databases.view', $host->id) }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button @click="deleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-gray-800 p-6 rounded-lg">
        <h3 class="text-lg text-white font-semibold mb-4">Databases</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Server</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Database Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Connections From</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Max Connections</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Manage</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($databases as $database)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white"><a href="{{ route('admin.servers.view', $database->getRelation('server')->id) }}" class="text-indigo-400 hover:text-indigo-300">{{ $database->getRelation('server')->name }}</a></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $database->database }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $database->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $database->remote }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                @if($database->max_connections != null)
                                    {{ $database->max_connections }}
                                @else
                                    Unlimited
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.servers.view.database', $database->getRelation('server')->id) }}" class="text-indigo-400 hover:text-indigo-300">Manage</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($databases->hasPages())
            <div class="mt-4">
                {{ $databases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#pNodeId').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endsection
