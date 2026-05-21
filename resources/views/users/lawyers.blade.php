<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Lawyers</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">Lawyer-Client Assignments</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">View all clients assigned to each lawyer in the system.</p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Back to Users</a>
        </div>

        <div class="space-y-6">
            @forelse($lawyers as $lawyer)
                <div class="rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">{{ $lawyer->name }}</h2>
                        <p class="text-sm text-slate-600">{{ $lawyer->email }}</p>
                    </div>
                    <div class="p-6">
                        <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Assigned Clients</h3>
                        @if($lawyer->clients->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($lawyer->clients as $client)
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                        <h4 class="font-semibold text-slate-900">{{ $client->full_name }}</h4>
                                        <p class="text-sm text-slate-600">{{ $client->email }}</p>
                                        <p class="text-sm text-slate-600">{{ $client->phone }}</p>
                                        <p class="text-xs text-slate-500 mt-2">{{ $client->client_type }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500">No clients assigned.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-100 bg-white p-6 text-center">
                    <p class="text-slate-500">No lawyers found.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>