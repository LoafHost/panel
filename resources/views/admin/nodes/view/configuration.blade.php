@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Configuration
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">{{ $node->name }}<small class="text-gray-400"> :: Configuration</small></h1>
    <p class="text-sm text-gray-400">Your daemon configuration file.</p>
@endsection

@section('content')
<div class="mt-8">
    <div class="flex items-center mb-4 text-sm text-gray-400">
        <a href="{{ route('admin.nodes.view', $node->id) }}" class="hover:text-white">About</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.settings', $node->id) }}" class="hover:text-white">Settings</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.configuration', $node->id) }}" class="text-white font-medium">Configuration</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.allocation', $node->id) }}" class="hover:text-white">Allocation</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.nodes.view.servers', $node->id) }}" class="hover:text-white">Servers</a>
    </div>

    <div x-data="{
        open: false,
        token: '',
        command: '',
        generateToken() {
            fetch('{{ route('admin.nodes.view.configuration.token', $node->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    this.token = data.token;
                    this.command = `cd /etc/loafpanel && sudo loaf-wings configure --panel-url {{ config('app.url') }} --token ${data.token} --node {{ $node->id }}${{ config('app.debug') ? ' --allow-insecure' : '' }}`;
                    this.open = true;
                } else {
                    alert('Error: Could not generate token.');
                }
            })
            .catch(() => {
                alert('An error occurred while generating the token.');
            });
        }
    }">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-white mb-4">Configuration File</h3>
                <div class="bg-gray-900 rounded-md p-4">
                    <pre class="text-sm text-gray-200 whitespace-pre-wrap break-all">{{ $node->getYamlConfiguration() }}</pre>
                </div>
                <div class="mt-4 text-xs text-gray-400">
                    <p>This file should be placed in your daemon's root directory (usually <code>/etc/loafpanel</code>) in a file called <code>config.yml</code>.</p>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-white mb-4">Auto-Deploy</h3>
                <p class="text-sm text-gray-400 mb-4">
                    Use the button below to generate a custom deployment command that can be used to configure wings on the target server with a single command.
                </p>
                <button @click="generateToken" type="button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">
                    Generate Token
                </button>
            </div>
        </div>

        <!-- Token Modal -->
        <div x-show="open" x-cloak class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-white mb-4">Auto-Configuration Command</h3>
                        <div class="bg-gray-900 rounded-md p-4">
                            <p class="text-gray-300">To auto-configure your node, run the following command:</p>
                            <pre x-text="command" class="text-sm text-gray-200 whitespace-pre-wrap break-all mt-2"></pre>
                        </div>
                    </div>
                    <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="open = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
