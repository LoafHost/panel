@extends('layouts.admin')

@section('title')
    List Users
@endsection

@section('content_header')
    <h1 class="text-2xl font-semibold text-white">Users</h1>
    <p class="text-sm text-gray-400">All registered users on the system.</p>
@endsection

@section('content')
<div class="bg-gray-800 p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-4">
        <form action="{{ route('admin.users') }}" method="GET" class="flex items-center">
            <input type="text" name="filter[email]" class="form-input bg-gray-900 border-gray-700 text-white rounded-l-md focus:ring-indigo-500 focus:border-indigo-500" value="{{ request()->input('filter.email') }}" placeholder="Search by Email">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r-md shadow-md transition duration-150 ease-in-out"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('admin.users.new') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">Create New</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900">
                <tr>
                    <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                    <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                    <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Client Name</th>
                    <th scope="col" class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Username</th>
                    <th scope="col" class="p-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">2FA</th>
                    <th scope="col" class="p-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider" title="Servers that this user is marked as the owner of.">Owned</th>
                    <th scope="col" class="p-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider" title="Servers that this user can access because they are marked as a subuser.">Access</th>
                    <th scope="col" class="relative p-3"></th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-700">
                        <td class="p-3 whitespace-nowrap text-sm text-gray-300"><code>{{ $user->id }}</code></td>
                        <td class="p-3 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.users.view', $user->id) }}" class="text-indigo-400 hover:text-indigo-300">{{ $user->email }}</a>
                            @if($user->root_admin)
                                <i class="fas fa-star text-yellow-400 ml-1" title="Administrator"></i>
                            @endif
                        </td>
                        <td class="p-3 whitespace-nowrap text-sm text-gray-300">{{ $user->name_first }} {{ $user->name_last }}</td>
                        <td class="p-3 whitespace-nowrap text-sm text-gray-300">{{ $user->username }}</td>
                        <td class="p-3 text-center">
                            @if($user->use_totp)
                                <i class="fas fa-lock text-green-500"></i>
                            @else
                                <i class="fas fa-unlock text-red-500"></i>
                            @endif
                        </td>
                        <td class="p-3 text-center whitespace-nowrap text-sm">
                            <a href="{{ route('admin.servers', ['filter[owner_id]' => $user->id]) }}" class="text-indigo-400 hover:text-indigo-300">{{ $user->servers_count }}</a>
                        </td>
                        <td class="p-3 text-center whitespace-nowrap text-sm text-gray-300">{{ $user->subuser_of_count }}</td>
                        <td class="p-3 text-center"><img src="https://www.gravatar.com/avatar/{{ md5(strtolower($user->email)) }}?s=100" class="h-8 w-8 rounded-full" /></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="mt-4 bg-gray-800 px-4 py-3 flex items-center justify-between sm:px-6">
            {{ $users->appends(['query' => Request::input('query')])->render() }}
        </div>
    @endif
</div>
@endsection
