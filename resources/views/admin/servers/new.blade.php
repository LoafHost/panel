@extends('layouts.modern')

@section('title')
    Create Server
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Create Server<small class="text-gray-400">Add a new server to the panel.</small></h1>
@endsection

@section('content')
<form action="{{ route('admin.servers.new') }}" method="POST">
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-xl text-white font-semibold mb-4">Core Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pName" class="form-label">Server Name</label>
                        <input type="text" id="pName" name="name" class="form-input" value="{{ old('name') }}" placeholder="Server Name">
                        <p class="form-text">Character limits: <code>a-z A-Z 0-9 _ - .</code> and <code>[Space]</code>.</p>
                    </div>
                    <div x-data="userSearch()" x-init="init()">
                        <label for="pUserId" class="form-label">Server Owner</label>
                        <select id="pUserId" name="owner_id" class="form-select"></select>
                        <p class="form-text">Email address of the Server Owner.</p>
                    </div>
                </div>
                <div class="mt-6">
                    <label for="pDescription" class="form-label">Server Description</label>
                    <textarea id="pDescription" name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
                    <p class="form-text">A brief description of this server.</p>
                </div>
                <div class="mt-6">
                    <div class="flex items-center">
                        <input id="pStartOnCreation" name="start_on_completion" type="checkbox" class="form-checkbox" {{ \LoafPanel\Helpers\Utilities::checked('start_on_completion', 1) }} />
                        <label for="pStartOnCreation" class="ml-2 text-white">Start Server when Installed</label>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8" x-data="allocationManager()" x-init="init()">
                <h3 class="text-xl text-white font-semibold mb-4">Allocation Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="pNodeId" class="form-label">Node</label>
                        <select name="node_id" id="pNodeId" class="form-select" x-model="node" @change="loadAllocations">
                            <option value="" disabled>Select a Node</option>
                            @foreach($locations as $location)
                                <optgroup label="{{ $location->long }} ({{ $location->short }})">
                                    @foreach($location->nodes as $node)
                                        <option value="{{ $node->id }}" data-location="{{ $location->id }}" @if(old('node_id') == $node->id) selected @endif>{{ $node->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <p class="form-text">The node this server will be deployed to.</p>
                    </div>
                    <div>
                        <label for="pAllocation" class="form-label">Default Allocation</label>
                        <select id="pAllocation" name="allocation_id" class="form-select" x-ref="defaultAllocation"></select>
                        <p class="form-text">The main allocation for this server.</p>
                    </div>
                    <div>
                        <label for="pAllocationAdditional" class="form-label">Additional Allocations</label>
                        <select id="pAllocationAdditional" name="allocation_additional[]" class="form-select" multiple x-ref="additionalAllocations"></select>
                        <p class="form-text">Additional allocations to assign.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-xl text-white font-semibold mb-4">Application Feature Limits</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="pDatabaseLimit" class="form-label">Database Limit</label>
                        <input type="number" id="pDatabaseLimit" name="database_limit" class="form-input" value="{{ old('database_limit', 0) }}"/>
                        <p class="form-text">Total databases allowed for this server.</p>
                    </div>
                    <div>
                        <label for="pAllocationLimit" class="form-label">Allocation Limit</label>
                        <input type="number" id="pAllocationLimit" name="allocation_limit" class="form-input" value="{{ old('allocation_limit', 0) }}"/>
                        <p class="form-text">Total allocations allowed for this server.</p>
                    </div>
                    <div>
                        <label for="pBackupLimit" class="form-label">Backup Limit</label>
                        <input type="number" id="pBackupLimit" name="backup_limit" class="form-input" value="{{ old('backup_limit', 0) }}"/>
                        <p class="form-text">Total backups allowed for this server.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-xl text-white font-semibold mb-4">Resource Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="pCPU" class="form-label">CPU Limit (%)</label>
                        <input type="number" id="pCPU" name="cpu" class="form-input" value="{{ old('cpu', 0) }}" />
                        <p class="form-text">Set to <code>0</code> for unlimited. E.g., <code>100</code> for one core, <code>200</code> for two.</p>
                    </div>
                    <div>
                        <label for="pThreads" class="form-label">CPU Pinning</label>
                        <input type="text" id="pThreads" name="threads" class="form-input" value="{{ old('threads') }}" placeholder="e.g. 0,1-3,4" />
                        <p class="form-text">Advanced: Specify CPU cores. Leave blank for all.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="pMemory" class="form-label">Memory (MiB)</label>
                        <input type="number" id="pMemory" name="memory" class="form-input" value="{{ old('memory') }}" />
                        <p class="form-text">Maximum memory. <code>0</code> for unlimited.</p>
                    </div>
                    <div>
                        <label for="pSwap" class="form-label">Swap (MiB)</label>
                        <input type="number" id="pSwap" name="swap" class="form-input" value="{{ old('swap', 0) }}" />
                        <p class="form-text"><code>0</code> to disable, <code>-1</code> for unlimited.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="pDisk" class="form-label">Disk Space (MiB)</label>
                        <input type="number" id="pDisk" name="disk" class="form-input" value="{{ old('disk') }}" />
                        <p class="form-text"><code>0</code> for unlimited disk usage.</p>
                    </div>
                    <div>
                        <label for="pIO" class="form-label">Block IO Weight</label>
                        <input type="number" id="pIO" name="io" class="form-input" value="{{ old('io', 500) }}" />
                        <p class="form-text">Value between <code>10</code> and <code>1000</code>.</p>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="pOomDisabled" name="oom_disabled" value="0" class="form-checkbox" {{ \LoafPanel\Helpers\Utilities::checked('oom_disabled', 0) }} />
                        <label for="pOomDisabled" class="ml-2 text-white">Enable OOM Killer</label>
                        <p class="form-text ml-4">Terminates the server if it breaches memory limits.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8" x-data="nestAndEggManager()" x-init="init()">
                <h3 class="text-xl text-white font-semibold mb-4">Nest Configuration</h3>
                <div class="mb-6">
                    <label for="pNestId" class="form-label">Nest</label>
                    <select id="pNestId" name="nest_id" class="form-select" x-model="nest" @change="loadEggs">
                        <option value="" disabled>Select a Nest</option>
                        @foreach($nests as $nest)
                            <option value="{{ $nest->id }}" @if(old('nest_id') == $nest->id) selected @endif>{{ $nest->name }}</option>
                        @endforeach
                    </select>
                    <p class="form-text">Select the Nest for this server.</p>
                </div>
                <div class="mb-6">
                    <label for="pEggId" class="form-label">Egg</label>
                    <select id="pEggId" name="egg_id" class="form-select" x-ref="egg"></select>
                    <p class="form-text">Select the Egg to define server operation.</p>
                </div>
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="pSkipScripting" name="skip_scripts" value="1" class="form-checkbox" {{ \LoafPanel\Helpers\Utilities::checked('skip_scripts', 0) }} />
                        <label for="pSkipScripting" class="ml-2 text-white">Skip Egg Install Script</label>
                    </div>
                    <p class="form-text">Skip the install script if the selected Egg has one.</p>
                </div>
            </div>

            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-xl text-white font-semibold mb-4">Docker Configuration</h3>
                <div>
                    <label for="pDefaultContainer" class="form-label">Docker Image</label>
                    <select id="pDefaultContainer" name="image" class="form-select"></select>
                    <input id="pDefaultContainerCustom" name="custom_image" value="{{ old('custom_image') }}" class="form-input mt-4" placeholder="Or enter a custom image..."/>
                    <p class="form-text">Select a default image or enter a custom one.</p>
                </div>
            </div>

            <div class="bg-gray-800 shadow-md rounded-lg p-6">
                <h3 class="text-xl text-white font-semibold mb-4">Startup Configuration</h3>
                <div id="appendVariablesTo"></div>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 shadow-md rounded-lg p-6 mt-8">
        {!! csrf_field() !!}
        <button type="submit" class="btn bg-green-500 hover:bg-green-600 text-white w-full">Create Server</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function userSearch() {
        return {
            init() {
                let self = this;
                $('#pUserId').select2({
                    ajax: {
                        url: '{{ route("admin.users.json") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { q: params.term };
                        },
                        processResults: function (data) {
                            return { results: data };
                        }
                    },
                    placeholder: 'Select a user',
                    allowClear: true,
                    minimumInputLength: 2,
                    templateResult: function(data) { return data.name_first + ' ' + data.name_last + ' <' + data.email + '>'; },
                    templateSelection: function(data) { return data.name_first + ' ' + data.name_last; }
                });

                @if(old('owner_id'))
                $.ajax({
                    url: '{{ route("admin.users.json") }}?user_id={{ old("owner_id") }}',
                    dataType: 'json',
                }).then(function (data) {
                    let option = new Option(data[0].name_first + ' ' + data[0].name_last, data[0].id, true, true);
                    $('#pUserId').append(option).trigger('change');
                });
                @endif
            }
        }
    }

    function allocationManager() {
        return {
            node: '{{ old("node_id") }}',
            init() {
                if (this.node) {
                    this.loadAllocations();
                }
            },
            loadAllocations() {
                let self = this;
                $.get('/admin/nodes/' + self.node + '/allocations', function(data) {
                    let defaultAllocation = $('#pAllocation');
                    let additionalAllocations = $('#pAllocationAdditional');

                    defaultAllocation.empty();
                    additionalAllocations.empty();

                    data.forEach(function(allocation) {
                        let option = new Option(allocation.ip + ':' + allocation.port, allocation.id);
                        if (allocation.assigned) {
                            $(option).attr('disabled', true);
                        }
                        defaultAllocation.append(option);
                        additionalAllocations.append(option.clone());
                    });

                    @if(old('allocation_id'))
                    defaultAllocation.val('{{ old("allocation_id") }}');
                    @endif

                    @if(old('allocation_additional'))
                    additionalAllocations.val({!! json_encode(old('allocation_additional')) !!});
                    @endif

                    defaultAllocation.select2({ placeholder: 'Select a default allocation' });
                    additionalAllocations.select2({ placeholder: 'Select additional allocations' });
                });
            }
        }
    }

    function nestAndEggManager() {
        return {
            nest: '{{ old("nest_id") }}',
            init() {
                let self = this;
                $('#pEggId').select2({ placeholder: 'Select an Egg' });

                if (this.nest) {
                    this.loadEggs();
                }

                $('#pNestId').on('change', function() {
                    self.nest = $(this).val();
                    self.loadEggs();
                });

                $('#pEggId').on('change', function() {
                    self.loadVariables($(this).val());
                });
            },
            loadEggs() {
                let self = this;
                $.get('/admin/nests/' + self.nest + '/eggs', function(data) {
                    let eggSelect = $('#pEggId');
                    eggSelect.empty();

                    data.forEach(function(egg) {
                        let option = new Option(egg.name, egg.id);
                        eggSelect.append(option);
                    });

                    @if(old('egg_id'))
                    eggSelect.val('{{ old("egg_id") }}').trigger('change');
                    @else
                    eggSelect.trigger('change');
                    @endif
                });
            },
            loadVariables(eggId) {
                let self = this;
                $.get('/admin/nests/' + self.nest + '/eggs/' + eggId + '/variables', function(data) {
                    let variablesContainer = $('#appendVariablesTo');
                    variablesContainer.empty();

                    data.forEach(function(variable) {
                        let html = `
                            <div class="mb-4">
                                <label class="form-label">${variable.name}</label>
                                <input type="${variable.is_editable ? 'text' : 'hidden'}" name="environment[${variable.env_variable}]" class="form-input" value="${variable.default_value}">
                                <p class="form-text">${variable.description}</p>
                            </div>
                        `;
                        variablesContainer.append(html);
                    });
                });
            }
        }
    }
</script>
@endpush
