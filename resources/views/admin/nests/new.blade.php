@extends('layouts.admin')

@section('title', 'Nests → New')

@section('content-header')
    <h1 class="text-3xl text-white font-bold">New Nest</h1>
    <p class="text-gray-400">Configure a new nest to deploy to all nodes.</p>
@endsection

@section('content')
<form action="{{ route('admin.nests.new') }}" method="POST">
    <div class="bg-gray-800 shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg text-white font-semibold">Create New Nest</h3>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" value="{{ old('name') }}" />
                <p class="text-sm text-gray-400 mt-2">This should be a descriptive category name that encompasses all of the eggs within the nest.</p>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-md text-white focus:ring-blue-500 focus:border-blue-500" rows="6">{{ old('description') }}</textarea>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-800 border-t border-gray-700 text-right">
            {!! csrf_field() !!}
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Create Nest</button>
        </div>
    </div>
</form>
@endsection
