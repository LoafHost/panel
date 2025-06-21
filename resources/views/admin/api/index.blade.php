@extends('layouts.admin')

@section('title')
    Application API
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Application API</h1>
    <p class="text-gray-400">Control access credentials for managing this Panel via the API.</p>
@endsection

@section('content')
<div class="bg-gray-800 shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl text-white font-semibold">Credentials List</h2>
        <a href="{{ route('admin.api.new') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Create New
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-900 rounded-lg">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Key</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Memo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Used</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider"></th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @foreach($keys as $key)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $key->identifier }}{{ decrypt($key->token) }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $key->memo }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                            @if(!is_null($key->last_used_at))
                                @datetimeHuman($key->last_used_at)
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">@datetimeHuman($key->created_at)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" data-action="revoke-key" data-attr="{{ $key->identifier }}" class="text-red-500 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('[data-action="revoke-key"]').click(function (event) {
                var self = $(this);
                event.preventDefault();
                Swal.fire({
                    title: 'Revoke API Key',
                    html: "Once this API key is revoked any applications currently using it will stop working.",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Revoke',
                    confirmButtonColor: '#d9534f',
                    background: '#1f2937',
                    color: '#ffffff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'DELETE',
                            url: '/admin/api/revoke/' + self.data('attr'),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).done(function () {
                            Swal.fire({
                                title: 'Success!',
                                text: 'API Key has been revoked.',
                                icon: 'success',
                                background: '#1f2937',
                                color: '#ffffff'
                            });
                            self.closest('tr').slideUp();
                        }).fail(function (jqXHR) {
                            console.error(jqXHR);
                            Swal.fire({
                                title: 'Whoops!',
                                text: 'An error occurred while attempting to revoke this key.',
                                icon: 'error',
                                background: '#1f2937',
                                color: '#ffffff'
                            });
                        });
                    }
                });
            });
        });
    </script>
@endsection
