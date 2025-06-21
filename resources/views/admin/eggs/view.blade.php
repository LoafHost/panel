@extends('layouts.admin')

@section('title')
    Nests &rarr; Egg: {{ $egg->name }}
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $egg->name }}</h1>
    <p class="text-gray-400">{{ str_limit($egg->description, 100) }}</p>
@endsection

@section('content')
<div class="mb-8">
    <div class="border-b border-gray-700">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('admin.nests.egg.view', $egg->id) }}" class="border-blue-500 text-white whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Configuration</a>
            <a href="{{ route('admin.nests.egg.variables', $egg->id) }}" class="border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Variables</a>
            <a href="{{ route('admin.nests.egg.scripts', $egg->id) }}" class="border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Install Script</a>
        </nav>
    </div>
</div>

<form action="{{ route('admin.nests.egg.view', $egg->id) }}" enctype="multipart/form-data" method="POST" class="mb-8">
    <div class="bg-gray-800 shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="pName" class="form-label">Import Egg File</label>
                <input type="file" name="import_file" class="form-input bg-gray-700 border-gray-600" />
                <p class="text-gray-400 text-sm mt-1">If you would like to replace settings for this Egg by uploading a new JSON file, simply select it here and press "Update Egg".</p>
            </div>
            <div class="text-right">
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PUT" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Update Egg</button>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('admin.nests.egg.view', $egg->id) }}" method="POST">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Configuration</h3>
            <div class="form-group">
                <label for="pName" class="form-label">Name <span class="text-red-500">*</span></label>
                <input type="text" id="pName" name="name" value="{{ $egg->name }}" class="form-input bg-gray-700 border-gray-600" />
                <p class="text-gray-400 text-sm mt-1">A simple, human-readable name to use as an identifier for this Egg.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pUuid" class="form-label">UUID</label>
                <input type="text" id="pUuid" readonly value="{{ $egg->uuid }}" class="form-input bg-gray-700 border-gray-600 cursor-not-allowed" />
                <p class="text-gray-400 text-sm mt-1">This is the globally unique identifier for this Egg which the Daemon uses as an identifier.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pAuthor" class="form-label">Author</label>
                <input type="text" id="pAuthor" readonly value="{{ $egg->author }}" class="form-input bg-gray-700 border-gray-600 cursor-not-allowed" />
                <p class="text-gray-400 text-sm mt-1">The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pDockerImages" class="form-label">Docker Images <span class="text-red-500">*</span></label>
                <textarea id="pDockerImages" name="docker_images" class="form-input bg-gray-700 border-gray-600" rows="4">{{ implode(PHP_EOL, $images) }}</textarea>
                <p class="text-gray-400 text-sm mt-1">
                    The docker images available to servers using this egg. Enter one per line. Users will be able to select from this list of images if more than one value is provided.
                    Optionally, a display name may be provided by prefixing the image with the name followed by a pipe character, and then the image URL. Example: <code>Display Name|ghcr.io/my/egg</code>
                </p>
            </div>
            <div class="form-group mt-4">
                <div class="flex items-center">
                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" class="form-checkbox bg-gray-700 border-gray-600" value="1" @if($egg->force_outgoing_ip) checked @endif />
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
                <label for="pDescription" class="form-label">Description</label>
                <textarea id="pDescription" name="description" class="form-input bg-gray-700 border-gray-600" rows="8">{{ $egg->description }}</textarea>
                <p class="text-gray-400 text-sm mt-1">A description of this Egg that will be displayed throughout the Panel as needed.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pStartup" class="form-label">Startup Command <span class="text-red-500">*</span></label>
                <textarea id="pStartup" name="startup" class="form-input bg-gray-700 border-gray-600" rows="8">{{ $egg->startup }}</textarea>
                <p class="text-gray-400 text-sm mt-1">The default startup command that should be used for new servers using this Egg.</p>
            </div>
            <div class="form-group mt-4">
                <label for="pConfigFeatures" class="form-label">Features</label>
                <select class="form-select bg-gray-700 border-gray-600" name="features[]" id="pConfigFeatures" multiple>
                    @foreach(($egg->features ?? []) as $feature)
                        <option value="{{ $feature }}" selected>{{ $feature }}</option>
                    @endforeach
                </select>
                <p class="text-gray-400 text-sm mt-1">Additional features belonging to the egg. Useful for configuring additional panel modifications.</p>
            </div>
        </div>
    </div>
    <div class="bg-gray-800 shadow-md rounded-lg p-6 mt-8">
        <h3 class="text-xl text-white font-semibold mb-4">Process Management</h3>
        <div class="bg-yellow-500 text-white p-4 rounded mb-4">
            <p>The following configuration options should not be edited unless you understand how this system works. If wrongly modified it is possible for the daemon to break.</p>
            <p>All fields are required unless you select a separate option from the 'Copy Settings From' dropdown, in which case fields may be left blank to use the values from that Egg.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="form-group">
                    <label for="pConfigFrom" class="form-label">Copy Settings From</label>
                    <select name="config_from" id="pConfigFrom" class="form-select bg-gray-700 border-gray-600">
                        <option value="">None</option>
                        @foreach($egg->nest->eggs as $o)
                            <option value="{{ $o->id }}" {{ ($egg->config_from !== $o->id) ?: 'selected' }}>{{ $o->name }} &lt;{{ $o->author }}&gt;</option>
                        @endforeach
                    </select>
                    <p class="text-gray-400 text-sm mt-1">If you would like to default to settings from another Egg select it from the menu above.</p>
                </div>
                <div class="form-group mt-4">
                    <label for="pConfigStop" class="form-label">Stop Command</label>
                    <input type="text" id="pConfigStop" name="config_stop" class="form-input bg-gray-700 border-gray-600" value="{{ $egg->config_stop }}" />
                    <p class="text-gray-400 text-sm mt-1">The command that should be sent to server processes to stop them gracefully. If you need to send a <code>SIGINT</code> you should enter <code>^C</code> here.</p>
                </div>
                <div class="form-group mt-4">
                    <label for="pConfigLogs" class="form-label">Log Configuration</label>
                    <textarea data-action="handle-tabs" id="pConfigLogs" name="config_logs" class="form-input bg-gray-700 border-gray-600" rows="6">{{ ! is_null($egg->config_logs) ? json_encode(json_decode($egg->config_logs), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">This should be a JSON representation of where log files are stored, and whether or not the daemon should be creating custom logs.</p>
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label for="pConfigFiles" class="form-label">Configuration Files</label>
                    <textarea data-action="handle-tabs" id="pConfigFiles" name="config_files" class="form-input bg-gray-700 border-gray-600" rows="6">{{ ! is_null($egg->config_files) ? json_encode(json_decode($egg->config_files), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">This should be a JSON representation of configuration files to modify and what parts should be changed.</p>
                </div>
                <div class="form-group mt-4">
                    <label for="pConfigStartup" class="form-label">Start Configuration</label>
                    <textarea data-action="handle-tabs" id="pConfigStartup" name="config_startup" class="form-input bg-gray-700 border-gray-600" rows="6">{{ ! is_null($egg->config_startup) ? json_encode(json_decode($egg->config_startup), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '' }}</textarea>
                    <p class="text-gray-400 text-sm mt-1">This should be a JSON representation of what values the daemon should be looking for when booting a server to determine completion.</p>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <button id="deleteButton" type="submit" name="_method" value="DELETE" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-trash"></i> Delete
            </button>
            <div>
                <a href="{{ route('admin.nests.egg.export', $egg->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-2">Export</a>
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PATCH" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Save</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $(document).ready(function() {
        $('#pConfigFrom').select2({ theme: 'bootstrap-5' });
        $('#pConfigFeatures').select2({
            tags: true,
            selectOnClose: false,
            tokenSeparators: [',', ' '],
            theme: 'bootstrap-5'
        });

        $('#deleteButton').on('click', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won\'t be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                background: '#1f2937',
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest('form').submit();
                }
            })
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
    });
    </script>
@endsection
