@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Details
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">{{ $server->name }}<small class="text-gray-400 ml-2">Details</small></h1>
@endsection

@section('content')
@include('admin.servers.partials.navigation')
<div class="bg-gray-800 shadow-md rounded-lg p-6">
    <h3 class="text-xl text-white font-semibold mb-6">Base Information</h3>
    <form action="{{ route('admin.servers.view.details', $server->id) }}" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="form-label">Server Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $server->name) }}" class="form-input" />
                <p class="form-text">Character limits: <code>a-zA-Z0-9_-</code> and <code>[Space]</code>.</p>
            </div>
            <div>
                <label for="external_id" class="form-label">External Identifier</label>
                <input type="text" name="external_id" value="{{ old('external_id', $server->external_id) }}" class="form-input" />
                <p class="form-text">Leave empty to not assign an external identifier for this server.</p>
            </div>
            <div class="md:col-span-2" x-data="userSearch()" x-init="init()">
                <label for="pUserId" class="form-label">Server Owner <span class="text-red-500">*</span></label>
                <select name="owner_id" id="pUserId" class="form-select">
                    <option value="{{ $server->owner_id }}" selected>{{ $server->user->email }}</option>
                </select>
                <p class="form-text">Change the owner of this server. This will generate a new daemon security token if changed.</p>
            </div>
            <div class="md:col-span-2">
                <label for="description" class="form-label">Server Description</label>
                <textarea name="description" rows="4" class="form-input">{{ old('description', $server->description) }}</textarea>
                <p class="form-text">A brief description of this server.</p>
            </div>
        </div>
        <div class="mt-8 text-right">
            {!! csrf_field() !!}
            {!! method_field('PATCH') !!}
            <button type="submit" class="btn bg-primary-500 hover:bg-primary-600 text-white">
                Update Details
            </button>
        </div>
    </form>
</div>
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
                    templateResult: function(data) {
                        if (!data.id) return data.text;
                        return `<span>${data.name_first} ${data.name_last} &lt;${data.email}&gt;</span>`;
                    },
                    templateSelection: function(data) {
                        if (!data.id) return data.text;
                        if (data.name_first) {
                            return `${data.name_first} ${data.name_last}`;
                        }
                        return data.email; // fallback
                    }
                });

                // Pre-load the existing owner
                let initialOwner = {!! json_encode(['id' => $server->user->id, 'text' => $server->user->name_first . ' ' . $server->user->name_last]) !!};
                let option = new Option(initialOwner.text, initialOwner.id, true, true);
                $('#pUserId').append(option).trigger('change');
            }
        }
    }
</script>
@endpush
