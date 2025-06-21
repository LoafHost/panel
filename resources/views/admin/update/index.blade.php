@extends('layouts.modern')

@section('title', 'Update Loaf Panel')

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Update Loaf Panel</h1>
@endsection

@section('content')
<div class="bg-gray-800 shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <h3 class="text-xl text-white font-semibold mb-2">Current Version</h3>
            <p class="text-gray-300 text-lg">{{ $currentVersion }}</p>
        </div>
        <div>
            <h3 class="text-xl text-white font-semibold mb-2">Latest Version</h3>
            <p class="text-gray-300 text-lg">{{ $latestVersion }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900 border border-red-700 text-red-200 px-4 py-3 rounded-lg mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if ($isUpdateAvailable)
        <div class="mb-8">
            <h3 class="text-xl text-white font-semibold mb-4">Release Notes</h3>
            <div class="prose prose-invert max-w-none bg-gray-900 rounded-lg p-4">
                {!! Str::markdown($releaseNotes) !!}
            </div>
        </div>

        <form action="{{ route('admin.update.run') }}" method="POST">
            {!! csrf_field() !!}
            <button type="submit" class="btn bg-primary-500 hover:bg-primary-600 text-white">Update Now</button>
        </form>
    @else
        <p class="text-green-400">You are running the latest version of Loaf Panel.</p>
    @endif
</div>
@endsection
