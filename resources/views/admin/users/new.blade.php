@extends('layouts.admin')

@section('title')
    Create User
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">Create User</h1>
    <p class="text-sm text-gray-400">Add a new user to the system.</p>
@endsection

@section('content')
<form method="post">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Identity -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-white mb-4">Identity</h3>
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                    <input type="text" autocomplete="off" name="email" value="{{ old('email') }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
                    <input type="text" autocomplete="off" name="username" value="{{ old('username') }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="name_first" class="block text-sm font-medium text-gray-300">First Name</label>
                    <input type="text" autocomplete="off" name="name_first" value="{{ old('name_first') }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="name_last" class="block text-sm font-medium text-gray-300">Last Name</label>
                    <input type="text" autocomplete="off" name="name_last" value="{{ old('name_last') }}" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-300">Default Language</label>
                    <select name="language" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($languages as $key => $value)
                            <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">The default language for this user.</p>
                </div>
            </div>
        </div>

        <!-- Permissions & Password -->
        <div class="space-y-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-white mb-4">Permissions</h3>
                <div>
                    <label for="root_admin" class="block text-sm font-medium text-gray-300">Administrator</label>
                    <select name="root_admin" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="0">@lang('strings.no')</option>
                        <option value="1">@lang('strings.yes')</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Setting this to 'Yes' gives the user full administrative access.</p>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-white mb-4">Password</h3>
                <div class="p-3 bg-blue-900/50 border border-blue-800 rounded-md text-sm text-blue-200">
                    <p>Providing a password is optional. An email will be sent prompting the user to create one. If you provide a password, you must find another way to share it.</p>
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                    <input type="password" name="password" class="mt-1 block w-full bg-gray-700 border-gray-600 rounded-md shadow-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-gray-800 p-4 rounded-lg shadow-md text-right">
        {!! csrf_field() !!}
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">
            Create User
        </button>
    </div>
</form>
@endsection
