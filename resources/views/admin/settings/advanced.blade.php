@extends('layouts.modern')

@section('title', 'Advanced Settings')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold text-white mb-6">Advanced Settings</h1>
    <form action="{{ route('admin.settings.advanced') }}" method="POST">
        
        <!-- reCAPTCHA Settings -->
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">reCAPTCHA</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="recaptcha_enabled" class="block text-sm font-medium text-gray-300">Status</label>
                    <select id="recaptcha_enabled" name="recaptcha:enabled" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="true" {{ old('recaptcha:enabled', config('recaptcha.enabled')) ? 'selected' : '' }}>Enabled</option>
                        <option value="false" {{ !old('recaptcha:enabled', config('recaptcha.enabled')) ? 'selected' : '' }}>Disabled</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">If enabled, login and password reset forms will use a silent captcha.</p>
                </div>
                <div>
                    <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-300">Site Key</label>
                    <input type="text" id="recaptcha_site_key" name="recaptcha:website_key" value="{{ old('recaptcha:website_key', config('recaptcha.website_key')) }}" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-300">Secret Key</label>
                    <input type="text" id="recaptcha_secret_key" name="recaptcha:secret_key" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-2 text-sm text-gray-500">Used for communication between your site and Google. Keep it secret.</p>
                </div>
            </div>
            @if($showRecaptchaWarning)
                <div class="mt-4 bg-yellow-800 text-white p-4 rounded-lg">
                    You are using default reCAPTCHA keys. For better security, <a href="https://www.google.com/recaptcha/admin" class="underline">generate new invisible reCAPTCHA keys</a> for your site.
                </div>
            @endif
        </div>

        <!-- HTTP Connections Settings -->
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">HTTP Connections</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="connect_timeout" class="block text-sm font-medium text-gray-300">Connection Timeout</label>
                    <input type="number" id="connect_timeout" name="loafpanel:guzzle:connect_timeout" value="{{ old('loafpanel:guzzle:connect_timeout', config('loafpanel.guzzle.connect_timeout')) }}" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-2 text-sm text-gray-500">Time in seconds to wait for a connection before an error.</p>
                </div>
                <div>
                    <label for="request_timeout" class="block text-sm font-medium text-gray-300">Request Timeout</label>
                    <input type="number" id="request_timeout" name="loafpanel:guzzle:timeout" value="{{ old('loafpanel:guzzle:timeout', config('loafpanel.guzzle.timeout')) }}" required class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-2 text-sm text-gray-500">Time in seconds to wait for a request to complete before an error.</p>
                </div>
            </div>
        </div>

        <!-- Automatic Allocation Creation Settings -->
        <div class="bg-gray-900 rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">Automatic Allocation Creation</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="allocation_enabled" class="block text-sm font-medium text-gray-300">Status</label>
                    <select id="allocation_enabled" name="loafpanel:client_features:allocations:enabled" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="true" {{ old('loafpanel:client_features:allocations:enabled', config('loafpanel.client_features.allocations.enabled')) ? 'selected' : '' }}>Enabled</option>
                        <option value="false" {{ !old('loafpanel:client_features:allocations:enabled', config('loafpanel.client_features.allocations.enabled')) ? 'selected' : '' }}>Disabled</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Enable users to create allocations from the frontend.</p>
                </div>
                <div>
                    <label for="allocation_start_port" class="block text-sm font-medium text-gray-300">Starting Port</label>
                    <input type="number" id="allocation_start_port" name="loafpanel:client_features:allocations:range_start" value="{{ old('loafpanel:client_features:allocations:range_start', config('loafpanel.client_features.allocations.range_start')) }}" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-2 text-sm text-gray-500">The starting port in the allocatable range.</p>
                </div>
                <div>
                    <label for="allocation_end_port" class="block text-sm font-medium text-gray-300">Ending Port</label>
                    <input type="number" id="allocation_end_port" name="loafpanel:client_features:allocations:range_end" value="{{ old('loafpanel:client_features:allocations:range_end', config('loafpanel.client_features.allocations.range_end')) }}" class="mt-1 block w-full bg-gray-800 border-gray-700 text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="mt-2 text-sm text-gray-500">The ending port in the allocatable range.</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 rounded-lg shadow-lg p-6 flex justify-end">
            {!! csrf_field() !!}
            <button type="submit" name="_method" value="PATCH" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
