@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Delete
@endsection

@section('content')
<div x-data="deleteServerManager()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">{{ $server->name }}: Delete Server</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Safely Delete Server</h3>
            <div class="text-gray-400">
                <p>This action will attempt to delete the server from both the panel and daemon. If either one reports an error the action will be cancelled.</p>
                <p class="text-red-400 small mt-2">Deleting a server is an irreversible action. <strong>All server data</strong> (including files and users) will be removed from the system.</p>
            </div>
            <div class="mt-6">
                <button @click="openDeleteModal(false)" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-red-500">
                    Safely Delete This Server
                </button>
            </div>
        </div>
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-red-500">
            <h3 class="text-lg font-semibold text-white mb-4">Force Delete Server</h3>
            <div class="text-gray-400">
                <p>This action will attempt to delete the server from both the panel and daemon. If the daemon does not respond, or reports an error the deletion will continue.</p>
                <p class="text-red-400 small mt-2">Deleting a server is an irreversible action. <strong>All server data</strong> (including files and users) will be removed from the system. This method may leave dangling files on your daemon if it reports an error.</p>
            </div>
            <div class="mt-6">
                <button @click="openDeleteModal(true)" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-red-500">
                    Forcibly Delete This Server
                </button>
            </div>
        </div>
    </div>

    <!-- Deletion Modal -->
    <div x-show="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="deleteUrl" method="POST">
                    {!! csrf_field() !!}
                    <input type="hidden" name="force_delete" x-model="forceDelete">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-white" x-text="forceDelete ? 'Force Delete Server' : 'Safely Delete Server'"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">Are you sure you want to delete this server? All data will be permanently removed. This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Yes, Delete Server</button>
                        <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-700 shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-500 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function deleteServerManager() {
            return {
                showDeleteModal: false,
                forceDelete: false,
                deleteUrl: '{{ route("admin.servers.view.delete", $server->id) }}',

                openDeleteModal(force = false) {
                    this.forceDelete = force;
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
@endsection
