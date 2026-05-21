<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Users</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">User management</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">Create new system users and manage existing accounts from the admin panel.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('users.lawyers') }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">View Lawyer Assignments</a>
                <a href="{{ route('users.create') }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">+ Add User</a>
            </div>
        </div>

        @if(session('success') || session('error'))
            <div class="rounded-3xl border px-5 py-4 text-sm shadow-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-4 font-semibold">Name</th>
                        <th class="px-4 py-4 font-semibold">Email</th>
                                <th class="px-4 py-4 font-semibold">Role</th>
                        <th class="px-4 py-4 font-semibold">Joined</th>
                        <th class="px-4 py-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-4 text-slate-900 font-semibold">{{ $user->name }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-4 text-slate-700">{{ $user->role }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-4">
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user account?');" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">Delete</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Current user</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
