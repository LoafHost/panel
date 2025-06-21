@extends('layouts.admin')

@section('title')
    Nests &rarr; New Egg
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">New Egg</h1>
    <p class="text-gray-400">Create a new Egg to assign to servers.</p>
@endsection

@section('content')
<form action="{{ route('admin.nests.egg.new') }}" method="POST">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Configuration</h3>
            <div class="form-group">
                <label for="pNestId" class="form-label">Associated Nest</label>
                <select name="nest_id" id="pNestId" class="form-select bg-gray-700 border-gray-600">
                    @foreach($nests as $nest)
                        <option value="{{ $nest->id }}" {{ old('nest_id') != $nest->id ?: 'selected' }}>{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                    @endforeach
                </select>
                <p class="text-gray-400 text-sm mt-1">Think of a Nest as a category. You can put multiple Eggs in a nest, but consider putting only Eggs that are related to each other in each Nest.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pName" class="form-label">Name</label>
                <input type="text" id="pName" name="name" value="{{ old('name') }}" class="form-input bg-gray-700 border-gray-600" />
                <p class="text-gray-400 text-sm mt-1">A simple, human-readable name to use as an identifier for this Egg. This is what users will see as their game server type.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pDescription" class="form-label">Description</label>
                <textarea id="pDescription" name="description" class="form-input bg-gray-700 border-gray-600" rows="8">{{ old('description') }}</textarea>
                <p class="text-gray-400 text-sm mt-1">A description of this Egg.</p>
            </div>
            <div class="form-group mt-4">
                <div class="flex items-center">
                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" class="form-checkbox bg-gray-700 border-gray-600" value="1" {{ \LoafPanel\Helpers\Utilities::checked('force_outgoing_ip', 0) }} />
                    <label for="pForceOutgoingIp" class="ml-2 text-white font-medium">Force Outgoing IP</label>
                </div>
                <p class="text-gray-400 text-sm mt-1">
                    Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.
                    Required for certain games to work properly when the Node has multiple public IP addresses.
                    <br>
                    <strong class="text-yellow-400">
                        Enabling this option will disable internal networking for any servers using this egg,
                        causing them to be unable to internally access other servers on the same node.
                    </strong>
                </p>
            </div>
        </div>
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <div class="form-group">
                <label for="pDockerImages" class="control-label">Docker Images</label>
                <textarea id="pDockerImages" name="docker_images" rows="4" placeholder="quay.io/pterodactyl/service" class="form-input bg-gray-700 border-gray-600">{{ old('docker_images') }}</textarea>
                <p class="text-gray-400 text-sm mt-1">The docker images available to servers using this egg. Enter one per line. Users will be able to select from this list of images if more than one value is provided.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pStartup" class="control-label">Startup Command</label>
                <textarea id="pStartup" name="startup" class="form-input bg-gray-700 border-gray-600" rows="10">{{ old('startup') }}</textarea>
                <p class="text-gray-400 text-sm mt-1">The default startup command that should be used for new servers created with this Egg. You can change this per-server as needed.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pConfigFeatures" class="control-label">Features</label>
                <select class="form-select bg-gray-700 border-gray-600" name="features[]" id="pConfigFeatures" multiple>
                </select>
                <p class="text-gray-400 text-sm mt-1">Additional features belonging to the egg. Useful for configuring additional panel modifications.</p>
            </div>
        </div>
    </div>
    <div class="bg-gray-800 shadow-md rounded-lg p-6 mt-8">
        <h3 class="text-xl text-white font-semibold mb-4">Process Management</h3>
        <div class="bg-yellow-500 text-white p-4 rounded mb-4">
            <p>All fields are required unless you select a separate option from the 'Copy Settings From' dropdown, in which case fields may be left blank to use the values from that option.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="form-group">
                    <label for="pConfigFrom" class="form-label">Copy Settings From</label>
                    <select name="config_from" id="pConfigFrom" class="form-select bg-gray-700 border-gray-600">
                        <option value="">None</option>
                    </select>
                    <p class="text-gray-400 text-sm mt-1">If you would like to default to settings from another Egg select it from the dropdown above.</p>
                </div>
                <div class="form-group mt-4">
                    <label for="pConfigStop" class="form-label">Stop Command</label>
                    <input type="text" id="pConfigStop" name="config_stop" class="form-input bg-gray-700 border-gray-600" value="{{ old('config_stop') }}" />
                    <p class="text-gray-400 text-sm mt-1">The command that should be sent to server processes to stop them gracefully. If you need to send a <code>SIGINT</code> you should enter <code>^C</code> here.</p>
                </div>
                <div class="form-group mt-4">
                    <label for="pConfigLogs" class="form-label">Log Configuration</label>
                    <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-input bg-gray-700 border-gray-600" rows="6">{{ old('config_logs') }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">This should be a JSON representation of where log files are stored, and whether or not the daemon should be creating custom logs.</p>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="pConfigFiles" class="form-label">Configuration Files</label>
                    <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-input bg-gray-700 border-gray-600" rows="6">{{ old('config_files') }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">This should be a JSON representation of configuration files to modify and what parts should be changed.</p>
                </div>
                <div class="form-group mt-4">
                    <label for="pConfigStartup" class="form-label">Start Configuration</label>
                    <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-input bg-gray-700 border-gray-600" rows="6">{{ old('config_startup') }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">This should be a JSON representation of what values the daemon should be looking for when booting a server to determine completion.</p>
                </div>
            </div>
        </div>
        <div class="mt-6 text-right">
            {!! csrf_field() !!}
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Create</button>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    {!! Theme::js('vendor/lodash/lodash.js') !!}
    <script>
    $(document).ready(function() {
        $('#pNestId').select2({ theme: 'bootstrap-5' }).change();
        $('#pConfigFrom').select2({ theme: 'bootstrap-5' });
    });
    $('#pNestId').on('change', function (event) {
        $('#pConfigFrom').html('<option value="">None</option>').select2({
            theme: 'bootstrap-5',
            data: $.map(_.get(Pterodactyl.nests, $(this).val() + '.eggs', []), function (item) {
                return {
                    id: item.id,
                    text: item.name + ' <' + item.author + '>',
                };
            }),
        });
    });
    $('textarea[data-action="handle-tabs"]').on('keydown', function(event) {
        if (event.keyCode === 9) {
            event.preventDefault();

            var curPos = $(this)[0].selectionStart;
            var prepend = $(this).val().substr(0, curPos);
            var append = $(this).val().substr(curPos);

            $(this).val(prepend + '    ' + append);
        }
    });
    $('#pConfigFeatures').select2({
        tags: true,
        selectOnClose: false,
        tokenSeparators: [',', ' '],
        theme: 'bootstrap-5'
    });
    </script>
@endsection
