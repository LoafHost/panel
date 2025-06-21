@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Build Details
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $server->name }}<small class="text-gray-400 ml-2">Build Configuration</small></h1>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<form action="{{ route('admin.servers.view.build', $server->id) }}" method="POST">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Resource Management -->
        <div class="bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl text-white font-semibold mb-6">Resource Management</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cpu" class="form-label">CPU Limit (%)</label>
                    <input type="number" name="cpu" class="form-input" value="{{ old('cpu', $server->cpu) }}"/>
                    <p class="form-text">Set to <code>0</code> for unlimited. <code>100</code> = 1 core.</p>
                </div>
                <div>
                    <label for="threads" class="form-label">CPU Pinning</label>
                    <input type="text" name="threads" class="form-input" value="{{ old('threads', $server->threads) }}" placeholder="e.g. 0,1-3,4"/>
                    <p class="form-text">Advanced: Specify CPU cores.</p>
                </div>
                <div>
                    <label for="memory" class="form-label">Memory (MiB)</label>
                    <input type="number" name="memory" class="form-input" value="{{ old('memory', $server->memory) }}"/>
                    <p class="form-text">Set to <code>0</code> for unlimited.</p>
                </div>
                <div>
                    <label for="swap" class="form-label">Swap (MiB)</label>
                    <input type="number" name="swap" class="form-input" value="{{ old('swap', $server->swap) }}"/>
                    <p class="form-text"><code>0</code> to disable, <code>-1</code> for unlimited.</p>
                </div>
                <div>
                    <label for="disk" class="form-label">Disk Space (MiB)</label>
                    <input type="number" name="disk" class="form-input" value="{{ old('disk', $server->disk) }}"/>
                    <p class="form-text">Set to <code>0</code> for unlimited.</p>
                </div>
                <div>
                    <label for="io" class="form-label">Block IO Weight</label>
                    <input type="number" name="io" class="form-input" value="{{ old('io', $server->io) }}"/>
                    <p class="form-text">Value between <code>10</code>-<code>1000</code>.</p>
                </div>
            </div>
            <div class="mt-6">
                <label class="form-label">OOM Killer</label>
                <div class="flex items-center bg-gray-900 rounded-lg p-2">
                    <label class="flex-1 text-center p-2 rounded-lg cursor-pointer" :class="{ 'bg-red-500 text-white': !oom_disabled, 'bg-gray-700 text-gray-300': oom_disabled }">
                        <input type="radio" name="oom_disabled" value="0" class="hidden" x-model="oom_disabled" :value="false"> Enabled
                    </label>
                    <label class="flex-1 text-center p-2 rounded-lg cursor-pointer" :class="{ 'bg-green-500 text-white': oom_disabled, 'bg-gray-700 text-gray-300': !oom_disabled }">
                        <input type="radio" name="oom_disabled" value="1" class="hidden" x-model="oom_disabled" :value="true"> Disabled
                    </label>
                </div>
                <p class="form-text">Enabling OOM killer may cause server processes to exit unexpectedly.</p>
            </div>
        </div>

        <!-- Application Feature & Allocation -->
        <div>
            <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8">
                <h3 class="text-xl text-white font-semibold mb-6">Application Feature Limits</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="database_limit" class="form-label">Database Limit</label>
                        <input type="number" name="database_limit" class="form-input" value="{{ old('database_limit', $server->database_limit) }}"/>
                        <p class="form-text">Total databases allowed.</p>
                    </div>
                    <div>
                        <label for="allocation_limit" class="form-label">Allocation Limit</label>
                        <input type="number" name="allocation_limit" class="form-input" value="{{ old('allocation_limit', $server->allocation_limit) }}"/>
                        <p class="form-text">Total allocations allowed.</p>
                    </div>
                    <div>
                        <label for="backup_limit" class="form-label">Backup Limit</label>
                        <input type="number" name="backup_limit" class="form-input" value="{{ old('backup_limit', $server->backup_limit) }}"/>
                        <p class="form-text">Total backups allowed.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 shadow-md rounded-lg p-6" x-data="allocationSelector()" x-init="init()">
                <h3 class="text-xl text-white font-semibold mb-6">Allocation Management</h3>
                <div class="mb-6">
                    <label for="pAllocation" class="form-label">Default Connection</label>
                    <select id="pAllocation" name="allocation_id" class="form-select">
                        @foreach ($assigned as $assignment)
                            <option value="{{ $assignment->id }}" @if($assignment->id === $server->allocation_id) selected @endif>{{ $assignment->alias }}:{{ $assignment->port }}</option>
                        @endforeach
                    </select>
                    <p class="form-text">The primary connection address for this server.</p>
                </div>
                <div class="mb-6">
                    <label for="pAddAllocations" class="form-label">Assign Additional Ports</label>
                    <select name="add_allocations[]" id="pAddAllocations" class="form-select" multiple>
                        @foreach ($unassigned as $assignment)
                            <option value="{{ $assignment->id }}">{{ $assignment->alias }}:{{ $assignment->port }}</option>
                        @endforeach
                    </select>
                    <p class="form-text">Assign additional network ports to this server.</p>
                </div>
                <div>
                    <label for="pRemoveAllocations" class="form-label">Remove Additional Ports</label>
                    <select name="remove_allocations[]" id="pRemoveAllocations" class="form-select" multiple>
                        @foreach ($assigned as $assignment)
                            @if($assignment->id !== $server->allocation_id)
                                <option value="{{ $assignment->id }}">{{ $assignment->alias }}:{{ $assignment->port }}</option>
                            @endif
                        @endforeach
                    </select>
                    <p class="form-text">Select ports to remove from this server.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-800 shadow-md rounded-lg p-6 mt-8 text-right">
        {!! csrf_field() !!}
        {!! method_field('PATCH') !!}
        <button type="submit" class="btn bg-primary-500 hover:bg-primary-600 text-white">Update Build Configuration</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function allocationSelector() {
        return {
            init() {
                $('#pAllocation').select2({ placeholder: 'Select Default Allocation' });
                $('#pAddAllocations').select2({ placeholder: 'Select Ports to Add' });
                $('#pRemoveAllocations').select2({ placeholder: 'Select Ports to Remove' });
            }
        }
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('serverBuild', () => ({
            oom_disabled: {{ $server->oom_disabled ? 'true' : 'false' }}
        }));
    });
</script>
@endpush
