@extends('layouts.modern')

@section('title')
    Nests
@endsection

@section('content-header')
    <h1 class="text-3xl text-white font-bold">Nests</h1>
    <p class="text-gray-400">All nests currently available on this system.</p>
@endsection

@section('content')
<div class="bg-red-500 border-l-4 border-red-700 p-4 rounded-lg mb-6">
    <div class="flex">
        <div class="py-1"><svg class="h-6 w-6 text-white mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg></div>
        <div>
            <p class="text-white">Eggs are a powerful feature of Loaf Panel that allow for extreme flexibility and configuration. Please note that while powerful, modifying an egg wrongly can very easily brick your servers and cause more problems. Please avoid editing our default eggs — those provided by <code>support@loafpanel.io</code> — unless you are absolutely sure of what you are doing.</p>
        </div>
    </div>
</div>

<div x-data="{ importModal: false }">
    <div class="bg-gray-800 shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
            <h3 class="text-lg text-white font-semibold">Configured Nests</h3>
            <div class="space-x-2">
                <button @click="importModal = true" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-upload"></i> Import Egg
                </button>
                <a href="{{ route('admin.nests.new') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Create New</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-white">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Eggs</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Servers</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($nests as $nest)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap"><code class="bg-gray-700 text-gray-300 px-2 py-1 rounded">{{ $nest->id }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.nests.view', $nest->id) }}" class="text-blue-400 hover:text-blue-300">{{ $nest->name }}</a></td>
                            <td class="px-6 py-4 text-gray-300">{{ $nest->description }}</td>
                            <td class="px-6 py-4 text-center text-gray-300">{{ $nest->eggs_count }}</td>
                            <td class="px-6 py-4 text-center text-gray-300">{{ $nest->servers_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Egg Modal -->
    <div x-show="importModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="importModal" @click="importModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="importModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-white" id="modal-title">Import an Egg</h3>
                    <button @click="importModal = false" class="text-gray-400 hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('admin.nests.egg.import') }}" enctype="multipart/form-data" method="POST" class="mt-6">
                    {!! csrf_field() !!}
                    <div>
                        <label for="pImportFile" class="block text-sm font-medium text-gray-300">Egg File <span class="text-red-500">*</span></label>
                        <input id="pImportFile" type="file" name="import_file" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" accept="application/json" />
                        <p class="mt-2 text-sm text-gray-400">Select the <code>.json</code> file for the new egg that you wish to import.</p>
                    </div>
                    <div class="mt-4">
                        <label for="pImportToNest" class="block text-sm font-medium text-gray-300">Associated Nest <span class="text-red-500">*</span></label>
                        <select id="pImportToNest" name="import_to_nest" class="block w-full px-3 py-2 mt-1 text-white bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($nests as $nest)
                               <option value="{{ $nest->id }}">{{ $nest->name }} &lt;{{ $nest->author }}&gt;</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-gray-400">Select the nest that this egg will be associated with from the dropdown. If you wish to associate it with a new nest you will need to create that nest before continuing.</p>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="importModal = false" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-transparent rounded-md hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @parent
    <script>
        $(document).ready(function() {
            $('#pImportToNest').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#importServiceOptionModal')
            });
        });
    </script>
@endpush
