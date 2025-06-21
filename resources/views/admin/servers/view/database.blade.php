@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Databases
@endsection

@section('content')
<div x-data="databaseManager()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">{{ $server->name }}: Databases</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Active Databases</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Database</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Connections From</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Host</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Max Connections</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($server->databases as $database)
                                <tr class="text-gray-400" x-ref="row{{ $database->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $database->database }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $database->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $database->remote }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><code>{{ $database->host->host }}:{{ $database->host->port }}</code></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($database->max_connections != null)
                                            {{ $database->max_connections }}
                                        @else
                                            <span class="text-gray-500">Unlimited</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openResetModal({{ $database->id }})" class="text-blue-500 hover:text-blue-700 mr-2"><i class="fa fa-refresh"></i> Reset Password</button>
                                        <button @click="openDeleteModal({{ $database->id }})" class="text-red-500 hover:text-red-700"><i class="fa fa-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        No databases have been created for this server.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Create New Database</h3>
                <form action="{{ route('admin.servers.view.database', $server->id) }}" method="POST">
                    <div class="space-y-4">
                        <div>
                            <label for="pDatabaseHostId" class="block text-sm font-medium text-gray-300">Database Host</label>
                            <select id="pDatabaseHostId" name="database_host_id" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach($hosts as $host)
                                    <option value="{{ $host->id }}">{{ $host->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-gray-500">Select the host database server that this database should be created on.</p>
                        </div>
                        <div>
                            <label for="pDatabaseName" class="block text-sm font-medium text-gray-300">Database</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-600 bg-gray-700 text-gray-300 text-sm">s{{ $server->id }}_</span>
                                <input type="text" id="pDatabaseName" name="database" class="flex-1 block w-full rounded-none rounded-r-md bg-gray-700 border-gray-600 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="database">
                            </div>
                        </div>
                        <div>
                            <label for="pRemote" class="block text-sm font-medium text-gray-300">Connections</label>
                            <input type="text" id="pRemote" name="remote" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="%">
                            <p class="mt-2 text-sm text-gray-500">This should reflect the IP address that connections are allowed from. Uses standard MySQL notation. If unsure leave as <code>%</code>.</p>
                        </div>
                        <div>
                            <label for="pmax_connections" class="block text-sm font-medium text-gray-300">Concurrent Connections</label>
                            <input type="text" id="pmax_connections" name="max_connections" class="mt-1 block w-full bg-gray-700 border-gray-600 text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-2 text-sm text-gray-500">Max number of concurrent connections for this user. Leave empty for unlimited.</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        {!! csrf_field() !!}
                        <p class="text-sm text-gray-500 mb-2">A username and password for this database will be randomly generated.</p>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500">
                            Create Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Delete Database</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">Are you sure you want to delete this database? This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="deleteDatabase()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div x-show="showResetModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa fa-key text-blue-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">New Database Password</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">The password for this database has been reset. Please store it in a safe place, it will not be shown again.</p>
                                <div class="mt-4 bg-gray-900 rounded p-3">
                                    <code class="text-sm text-gray-300" x-text="newPassword"></code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="showResetModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function databaseManager() {
            return {
                showDeleteModal: false,
                showResetModal: false,
                databaseId: null,
                newPassword: '',

                openDeleteModal(id) {
                    this.databaseId = id;
                    this.showDeleteModal = true;
                },

                deleteDatabase() {
                    fetch('/admin/servers/view/{{ $server->id }}/database/' + this.databaseId + '/delete', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            // You can add a more sophisticated notification system here
                            alert(data.error);
                            return;
                        }
                        // On success, remove the row from the table
                        this.$refs['row' + this.databaseId].remove();
                        this.showDeleteModal = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                    });
                },

                openResetModal(id) {
                    this.databaseId = id;
                    fetch('/admin/servers/view/{{ $server->id }}/database/' + this.databaseId + '/reset-password', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.password) {
                            this.newPassword = data.password;
                            this.showResetModal = true;
                        } else {
                            alert(data.error || 'Could not reset password.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                    });
                }
            }
        }
    </script>
@endsection
