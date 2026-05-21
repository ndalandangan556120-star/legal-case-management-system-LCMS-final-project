<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Clients</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">Client dashboard</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">Manage all clients, monitor recent activity, and analyze client type distribution from one clean admin panel.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="GET" action="{{ route('clients.index') }}" class="flex w-full max-w-2xl items-center gap-2">
                    <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Search cases, clients, documents..."
                        class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm outline-none focus:border-slate-300" />
                    <button type="submit" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Search</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1.9fr_0.95fr]">
            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">All Clients</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Client list</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        @can('create', App\Models\Client::class)
                            <a href="{{ route('clients.create') }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">+ Add Client</a>
                        @endcan
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-3xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-4 font-semibold">Client Name</th>
                                <th class="px-4 py-4 font-semibold">Contact</th>
                                <th class="px-4 py-4 font-semibold">Client Type</th>
                                <th class="px-4 py-4 font-semibold">Active Cases</th>
                                <th class="px-4 py-4 font-semibold">Status</th>
                                <th class="px-4 py-4 font-semibold">Joined On</th>
                                <th class="px-4 py-4 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($clients as $client)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-sm font-semibold text-slate-900">{{ strtoupper(substr($client->full_name, 0, 1)) }}</div>
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $client->full_name }}</p>
                                                <p class="text-xs text-slate-500">{{ $client->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">{{ $client->phone }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $client->client_type }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $client->active_cases_count }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $client->active_cases_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $client->active_cases_count > 0 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">{{ $client->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-4">
                                        <a href="{{ route('clients.show', $client) }}" class="text-slate-900 font-semibold hover:text-slate-700">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No clients found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    {{ $clients->links() }}
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Clients by Type</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Type distribution</h3>
                        </div>
                    </div>
                    <div class="mt-6 relative h-56 w-full">
                        <canvas id="clientTypeChart"></canvas>
                    </div>
                    <div class="mt-5 space-y-3">
                        @foreach($clientTypeCounts as $type => $count)
                            <div class="flex items-center justify-between rounded-3xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $type }}</p>
                                    <p class="text-xs text-slate-500">{{ $count }} clients</p>
                                </div>
                                <span class="text-sm font-semibold text-slate-700">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Recent Clients</p>
                        <h3 class="mt-2 text-xl font-semibold text-slate-900">Latest signups</h3>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse($recentClients as $client)
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $client->full_name }}</p>
                                        <p class="text-sm text-slate-500">{{ $client->email }}</p>
                                    </div>
                                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $client->created_at->format('M d') }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No recent client activity yet.</p>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clientTypeCtx = document.getElementById('clientTypeChart');
            if (clientTypeCtx && window.Chart) {
                new Chart(clientTypeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_keys($clientTypeCounts)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($clientTypeCounts)) !!},
                            backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
        });
    </script>
</x-app-layout>
