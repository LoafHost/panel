@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Startup
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $server->name }}<small class="text-gray-400 ml-2">Startup Configuration</small></h1>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<form action="{{ route('admin.servers.view.startup', $server->id) }}" method="POST" x-data="startupEditor()" x-init="init()">
    <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-xl text-white font-semibold mb-4">Startup Command</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="pStartup" class="form-label">Startup Command</label>
                <input id="pStartup" name="startup" class="form-input font-mono" type="text" value="{{ old('startup', $server->startup) }}" />
                <p class="form-text">Edit the server's startup command. Default variables: <code>@{{SERVER_MEMORY}}</code>, <code>@{{SERVER_IP}}</code>, <code>@{{SERVER_PORT}}</code>.</p>
            </div>
            <div>
                <label for="pDefaultStartupCommand" class="form-label">Default Service Start Command</label>
                <input id="pDefaultStartupCommand" class="form-input font-mono" type="text" readonly />
                <p class="form-text">The default startup command for the selected Egg.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Service Configuration</h3>
            <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg mb-6" role="alert">
                <p><strong>This is a destructive operation in many cases.</strong> Changing these values will cause the server to be reinstalled. The server will be stopped immediately to proceed.</p>
            </div>
            <div class="space-y-6">
                <div>
                    <label for="pNestId" class="form-label">Nest</label>
                    <select name="nest_id" id="pNestId" class="form-select">
                        @foreach($nests as $nest)
                            <option value="{{ $nest->id }}" @if($nest->id === $server->nest_id) selected @endif>{{ $nest->name }}</option>
                        @endforeach
                    </select>
                    <p class="form-text">Select the Nest for this server.</p>
                </div>
                <div>
                    <label for="pEggId" class="form-label">Egg</label>
                    <select name="egg_id" id="pEggId" class="form-select"></select>
                    <p class="form-text">Select the Egg to define server operation.</p>
                </div>
                <div>
                    <label for="pDockerImage" class="form-label">Docker Image</label>
                    <select id="pDockerImage" name="docker_image" class="form-select"></select>
                    <input id="pDockerImageCustom" name="custom_docker_image" value="{{ old('custom_docker_image') }}" class="form-input mt-2" placeholder="Or enter a custom image..."/>
                    <p class="form-text">The Docker image for this server. Select from the list or provide a custom image.</p>
                </div>
                <div>
                    <div class="flex items-center">
                        <input id="pSkipScripting" name="skip_scripts" type="checkbox" class="form-checkbox" value="1" @if($server->skip_scripts) checked @endif />
                        <label for="pSkipScripting" class="ml-2 text-white">Skip Egg Install Script</label>
                    </div>
                    <p class="form-text">Skip the install script if the selected Egg has one.</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-4">Service Variables</h3>
            <div id="appendVariablesTo" class="space-y-4"></div>
        </div>
    </div>

    <div class="bg-gray-800 shadow-md rounded-lg p-6 text-right">
        {!! csrf_field() !!}
        <button type="submit" class="btn bg-primary-500 hover:bg-primary-600 text-white">Save Modifications</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function startupEditor() {
        return {
            nests: {!! json_encode($nests) !!},
            server: {!! json_encode($server) !!},
            server_variables: {!! json_encode($server->variables->pluck('variable_value', 'env_variable')) !!},
            selectedNestId: '{{ old("nest_id", $server->nest_id) }}',
            selectedEggId: '{{ old("egg_id", $server->egg_id) }}',

            init() {
                let self = this;
                $('#pNestId').select2().on('change', function() { self.selectedNestId = $(this).val(); self.updateEggs(); });
                $('#pEggId').select2().on('change', function() { self.selectedEggId = $(this).val(); self.updateEggDetails(); });
                $('#pDockerImage').select2().on('change', () => { $('#pDockerImageCustom').val(''); });

                this.updateEggs(true);
            },

            updateEggs(initial = false) {
                const nest = this.nests.find(n => n.id == this.selectedNestId);
                const eggSelect = $('#pEggId');
                eggSelect.empty();

                if (nest && nest.eggs) {
                    nest.eggs.forEach(egg => {
                        eggSelect.append(new Option(egg.name, egg.id));
                    });
                }

                if (initial) {
                    eggSelect.val(this.selectedEggId);
                }
                eggSelect.trigger('change');
            },

            updateEggDetails() {
                const nest = this.nests.find(n => n.id == this.selectedNestId);
                if (!nest) return;
                const egg = nest.eggs.find(e => e.id == this.selectedEggId);
                if (!egg) return;

                // Startup Command
                $('#pDefaultStartupCommand').val(egg.startup || nest.startup || 'Error: Startup Not Defined!');

                // Docker Images
                const imageSelect = $('#pDockerImage');
                imageSelect.empty();
                Object.entries(egg.docker_images).forEach(([name, image]) => {
                    imageSelect.append(new Option(`${name} (${image})`, image));
                });

                if (egg.id === this.server.egg_id) {
                    imageSelect.val(this.server.image);
                    if (imageSelect.val() !== this.server.image) {
                        $('#pDockerImageCustom').val(this.server.image);
                    }
                } else {
                    $('#pDockerImageCustom').val('');
                }
                imageSelect.trigger('change');

                // Variables
                const variablesContainer = $('#appendVariablesTo');
                variablesContainer.empty();
                if (egg.variables) {
                    egg.variables.forEach(variable => {
                        const value = this.server_variables[variable.env_variable] || variable.default_value;
                        const isRequired = variable.required ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-500 text-white">Required</span>' : '';
                        const variableHtml = `
                            <div>
                                <label class="form-label flex items-center">${variable.name} ${isRequired}</label>
                                <input name="environment[${variable.env_variable}]" class="form-input" type="text" value="${value}" />
                                <p class="form-text">${variable.description}</p>
                                <p class="form-text"><strong>Variable:</strong> <code>${variable.env_variable}</code> | <strong>Rules:</strong> <code>${variable.rules}</code></p>
                            </div>
                        `;
                        variablesContainer.append(variableHtml);
                    });
                }
            }
        }
    }
</script>
@endpush
