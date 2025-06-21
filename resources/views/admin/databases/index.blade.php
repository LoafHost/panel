@extends('layouts.admin')

@section('title')
    Database Hosts
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Database Hosts</h1>
    <p class="text-gray-400">Database hosts that servers can have databases created on.</p>
@endsection

@section('content')
<div class="bg-gray-800 shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl text-white font-semibold">Host List</h2>
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" data-toggle="modal" data-target="#newHostModal">
            Create New
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-900 rounded-lg">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Host</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Port</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Databases</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Node</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @foreach ($hosts as $host)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $host->id }}</code></td>
                        <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.databases.view', $host->id) }}" class="text-blue-400 hover:text-blue-500">{{ $host->name }}</a></td>
                        <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $host->host }}</code></td>
                        <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $host->port }}</code></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $host->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-300">{{ $host->databases_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if(! is_null($host->node))
                                <a href="{{ route('admin.nodes.view', $host->node->id) }}" class="text-blue-400 hover:text-blue-500">{{ $host->node->name }}</a>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-700 text-gray-300">None</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="newHostModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-gray-800 text-white">
            <form action="{{ route('admin.databases') }}" method="POST">
                <div class="modal-header border-b border-gray-700">
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Create New Database Host</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Name</label>
                        <input type="text" name="name" id="pName" class="form-input bg-gray-700 border-gray-600" />
                        <p class="text-gray-400 text-sm">A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, <code>us.nyc.lvl3</code>.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pHost" class="form-label">Host</label>
                            <input type="text" name="host" id="pHost" class="form-input bg-gray-700 border-gray-600" />
                            <p class="text-gray-400 text-sm">The IP address or FQDN that should be used when attempting to connect to this MySQL host <em>from the panel</em> to add new databases.</p>
                        </div>
                        <div>
                            <label for="pPort" class="form-label">Port</label>
                            <input type="text" name="port" id="pPort" class="form-input bg-gray-700 border-gray-600" value="3306"/>
                            <p class="text-gray-400 text-sm">The port that MySQL is running on for this host.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="pUsername" class="form-label">Username</label>
                            <input type="text" name="username" id="pUsername" class="form-input bg-gray-700 border-gray-600" />
                            <p class="text-gray-400 text-sm">The username of an account that has enough permissions to create new users and databases on the system.</p>
                        </div>
                        <div>
                            <label for="pPassword" class="form-label">Password</label>
                            <input type="password" name="password" id="pPassword" class="form-input bg-gray-700 border-gray-600" />
                            <p class="text-gray-400 text-sm">The password to the account defined.</p>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <label for="pNodeId" class="form-label">Linked Node</label>
                        <select name="node_id" id="pNodeId" class="form-select bg-gray-700 border-gray-600">
                            <option value="">None</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->short }}">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}">{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="text-gray-400 text-sm">This setting does nothing other than default to this database host when adding a database to a server on the selected node.</p>
                    </div>
                </div>
                <div class="modal-footer border-t border-gray-700">
                    <p class="text-red-500 text-sm text-left">The account defined for this database host <strong>must</strong> have the <code>WITH GRANT OPTION</code> permission. If the defined account does not have this permission requests to create databases <em>will</em> fail. <strong>Do not use the same account details for MySQL that you have defined for this panel.</strong></p>
                    {!! csrf_field() !!}
                    <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pNodeId').select2({
            theme: 'bootstrap-5'
        });
    </script>
@endsection
