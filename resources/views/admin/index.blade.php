@extends('layouts.admin')

@section('title')
    Administration
@endsection

@section('content-header')
    <h1 class="text-2xl font-semibold text-gray-100">Administrative Overview</h1>
    <p class="text-sm text-gray-400">A quick glance at your system.</p>
@endsection

@section('content-admin')
<div class="grid grid-cols-1">
    <div class="bg-gray-800 shadow-md rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-100 mb-4">System Information</h3>
        <div class="text-gray-300">
            @if ($version->isLatestPanel())
                You are running Loaf Panel version <code>{{ config('app.version') }}</code>. Your panel is up-to-date!
            @else
                Your panel is <strong>not up-to-date!</strong> The latest version is <a href="https://github.com/loaf-panel/panel/releases/v{{ $version->getPanel() }}" target="_blank" class="text-yellow-500 hover:underline"><code>{{ $version->getPanel() }}</code></a> and you are currently running version <code>{{ config('app.version') }}</code>.
            @endif
        </div>
    </div>
</div>

<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <a href="https://discord.gg/loafhosts" target="_blank" class="block bg-yellow-600 hover:bg-yellow-700 text-white font-semibold text-center py-3 px-4 rounded-lg transition duration-300">
        <i class="fa fa-fw fa-comments"></i> Get Help
    </a>
    <a href="#" class="block bg-gray-700 hover:bg-gray-600 text-white font-semibold text-center py-3 px-4 rounded-lg transition duration-300">
        <i class="fa fa-fw fa-book"></i> Documentation
    </a>
    <a href="https://github.com/loaf-panel/panel" target="_blank" class="block bg-gray-700 hover:bg-gray-600 text-white font-semibold text-center py-3 px-4 rounded-lg transition duration-300">
        <i class="fa fa-fw fa-github"></i> GitHub
    </a>
    <a href="#" class="block bg-green-600 hover:bg-green-700 text-white font-semibold text-center py-3 px-4 rounded-lg transition duration-300">
        <i class="fa fa-fw fa-heart"></i> Support the Project
    </a>
</div>
@endsection
