@extends('layouts.admin')

@section('title')
    Manage User: {{ $user->username }}
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">{{ $user->name_first }} {{ $user->name_last }}</h1>
    <p class="text-sm text-gray-400">{{ $user->username }}</p>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- User Details -->
    <div class="md:col-span-2">
        <form action="{{ route('admin.users.view', $user->id) }}" method="post">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-white mb-4">Identity</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                        <input type="text" name="username" value="{{ $user->username }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="name_first" class="block text-sm font-medium text-gray-300">First Name</label>
                        <input type="text" name="name_first" value="{{ $user->name_first }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="name_last" class="block text-sm font-medium text-gray-300">Last Name</label>
                        <input type="text" name="name_last" value="{{ $user->name_last }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="language" class="block text-sm font-medium text-gray-300">Default Language</label>
                        <select name="language" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($languages as $key => $value)
                                <option value="{{ $key }}" @if($user->language === $key) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400">The default language for this user.</p>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    {!! csrf_field() !!}
                    {!! method_field('PATCH') !!}
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">Update User</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Password & Permissions -->
    <div class="space-y-8">
        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-white mb-4">Password</h3>
            <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
            <input type="password" id="password" name="password" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
            <p class="mt-1 text-xs text-gray-400">Leave blank to keep the current password.</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-white mb-4">Permissions</h3>
            <label for="root_admin" class="block text-sm font-medium text-gray-300">Administrator</label>
            <select name="root_admin" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                <option value="0">@lang('strings.no')</option>
                <option value="1" {{ $user->root_admin ? 'selected="selected"' : '' }}>@lang('strings.yes')</option>
            </select>
            <p class="mt-1 text-xs text-gray-400">Setting this to 'Yes' gives full administrative access.</p>
        </div>

        <!-- Delete User -->
        <div class="bg-red-900/50 border border-red-700 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-red-200 mb-4">Delete User</h3>
            <p class="text-sm text-red-200 mb-4">There must be no servers associated with this account for it to be deleted.</p>
            <button id="deleteUserButton" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out" {{ $user->servers->count() < 1 ?: 'disabled' }}>
                Delete User
            </button>
        </div>
    </div>
</div>

<form id="delete-user-form" action="{{ route('admin.users.view', $user->id) }}" method="POST" style="display: none;">
    {!! csrf_field() !!}
    {!! method_field('DELETE') !!}
</form>
@endsection

@push('scripts')
    @parent
    <script>
        document.getElementById('deleteUserButton').addEventListener('click', function (event) {
            event.preventDefault();
            swal({
                title: 'Delete User',
                text: 'Are you sure you want to delete this user? This action cannot be undone.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d9534f',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete user'
            }, function(isConfirm) {
                if (isConfirm) {
                    document.getElementById('delete-user-form').submit();
                }
            });
        });
    </script>
@endpush
