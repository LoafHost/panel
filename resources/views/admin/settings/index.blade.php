@extends('layouts.modern')

@section('title', 'Application Settings')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold text-white mb-6">Application Settings</h1>
    <div class="bg-gray-900 rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.settings') }}" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Company Name -->
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-300">Company Name</label>
                    <input type="text" id="app_name" name="app:name" value="{{ old('app:name', config('app.name')) }}" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-2 text-sm text-gray-500">This is the name that is used throughout the panel and in emails sent to clients.</p>
                </div>

                <!-- 2-Factor Authentication -->
                <div>
                    <label class="block text-sm font-medium text-gray-300">Require 2-Factor Authentication</label>
                    <div class="mt-2 space-y-2">
                        @php
                            $level = old('loafpanel:auth:2fa_required', config('loafpanel.auth.2fa_required'));
                        @endphp
                        <div class="flex items-center">
                            <input id="2fa_not_required" name="loafpanel:auth:2fa_required" type="radio" value="0" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if ($level == 0) checked @endif>
                            <label for="2fa_not_required" class="ml-3 block text-sm font-medium text-gray-300">Not Required</label>
                        </div>
                        <div class="flex items-center">
                            <input id="2fa_admin_only" name="loafpanel:auth:2fa_required" type="radio" value="1" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if ($level == 1) checked @endif>
                            <label for="2fa_admin_only" class="ml-3 block text-sm font-medium text-gray-300">Admin Only</label>
                        </div>
                        <div class="flex items-center">
                            <input id="2fa_all_users" name="loafpanel:auth:2fa_required" type="radio" value="2" class="h-4 w-4 text-indigo-600 border-gray-700 bg-gray-800 focus:ring-indigo-500" @if ($level == 2) checked @endif>
                            <label for="2fa_all_users" class="ml-3 block text-sm font-medium text-gray-300">All Users</label>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">If enabled, any account falling into the selected grouping will be required to have 2-Factor authentication enabled to use the Panel.</p>
                </div>

                <!-- Default Language -->
                <div>
                    <label for="app_locale" class="block text-sm font-medium text-gray-300">Default Language</label>
                    <select id="app_locale" name="app:locale" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($languages as $key => $value)
                            <option value="{{ $key }}" @if(config('app.locale') === $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-sm text-gray-500">The default language to use when rendering UI components.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                {!! csrf_field() !!}
                <button type="submit" name="_method" value="PATCH" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
