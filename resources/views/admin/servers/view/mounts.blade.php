@extends('layouts.modern')

@section('title')
    Server — {{ $server->name }}: Mounts
@endsection

@section('content')
<div x-data="mountsManager()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-white">{{ $server->name }}: Mounts</h1>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Available Mounts</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach ($mounts as $mount)
                        <tr class="text-gray-400" x-ref="row_{{ $mount->id }}">
                            <td class="px-6 py-4 whitespace-nowrap"><code>{{ $mount->id }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('admin.mounts.view', $mount->id) }}" class="text-blue-400 hover:text-blue-600">{{ $mount->name }}</a></td>
                            <td class="px-6 py-4 whitespace-nowrap"><code>{{ $mount->source }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap"><code>{{ $mount->target }}</code></td>
                            <td class="px-6 py-4 whitespace-nowrap" x-html="getStatusBadge({{ in_array($mount->id, $serverMounts) }})"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" x-html="getActionButtons({{ $mount->id }}, {{ in_array($mount->id, $serverMounts) }})"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Unmount Modal -->
    <div x-show="showUnmountModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Unmount</h2>
                <p class="text-gray-600 mb-4">Are you sure you want to unmount this drive? This action cannot be undone.</p>
                <div class="flex justify-end">
                    <button @click="showUnmountModal = false" class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 hover:bg-gray-400 mr-2">Cancel</button>
                    <button @click="unmount" class="px-4 py-2 bg-red-600 rounded-md text-white hover:bg-red-700">Unmount</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
        function mountsManager() {
            return {
                serverMounts: {{ json_encode($serverMounts) }},
                showUnmountModal: false,
                mountToUnmount: null,

                getStatusBadge(isMounted) {
                    if (isMounted) {
                        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-300">Mounted</span>`;
                    } else {
                        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900 text-blue-300">Unmounted</span>`;
                    }
                },

                getActionButtons(mountId, isMounted) {
                    if (isMounted) {
                        return `<button @click="confirmUnmount(${mountId})" class="text-red-500 hover:text-red-700"><i class="fa fa-times"></i> Unmount</button>`;
                    } else {
                        return `<button @click="mount(${mountId})" class="text-green-500 hover:text-green-700"><i class="fa fa-plus"></i> Mount</button>`;
                    }
                },

                mount(mountId) {
                    fetch('{{ route("admin.servers.view.mounts.store", $server->id) }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ mount_id: mountId })
                    })
                    .then(res => res.json()).then(data => {
                        if(data.error) { alert(data.error); return; }
                        this.serverMounts.push(mountId);
                        this.updateRow(mountId, true);
                    });
                },

                confirmUnmount(mountId) {
                    this.mountToUnmount = mountId;
                    this.showUnmountModal = true;
                },

                unmount() {
                    fetch(`/admin/servers/view/{{ $server->id }}/mounts/${this.mountToUnmount}`,
                    {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => {
                        if(res.ok) {
                            this.serverMounts = this.serverMounts.filter(id => id !== this.mountToUnmount);
                            this.updateRow(this.mountToUnmount, false);
                            this.showUnmountModal = false;
                        } else {
                            alert('Could not unmount the drive.');
                        }
                    });
                },

                updateRow(mountId, isMounted) {
                    const row = this.$refs['row_' + mountId];
                    row.cells[4].innerHTML = this.getStatusBadge(isMounted);
                    row.cells[5].innerHTML = this.getActionButtons(mountId, isMounted);
                }
            }
        }
    </script>
@endsection
