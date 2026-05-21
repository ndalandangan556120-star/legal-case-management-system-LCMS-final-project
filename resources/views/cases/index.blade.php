<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                @if(Auth::user()->isLawyer())
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Cases</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">My Cases</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">Manage your assigned cases, track progress, and update case work from one place.</p>
                @else
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Cases</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">All cases</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">See active case status, recent activity, and manage case records from one clean admin view.</p>
                @endif
            </div>

        </div>

        <!-- Search Form -->
        <div class="flex w-full max-w-2xl items-center gap-2">
            <form method="GET" action="{{ route('cases.index') }}" class="flex w-full items-center gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases by number, title, description, or client..." class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pl-10 text-sm text-slate-700 outline-none focus:border-slate-300">
                    <svg class="absolute left-3 top-3.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Search</button>
                @if(request('search'))
                    <a href="{{ route('cases.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Clear</a>
                @endif
            </form>
        </div>

        @if(Auth::user()->isLawyer())
            <div class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="grid gap-4 lg:grid-cols-[1.9fr_1fr]">
                    <div class="relative">
                        <input type="text" placeholder="Search cases by title, case number or client..."
                            class="h-12 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 pr-12 text-sm text-slate-700 shadow-sm outline-none focus:border-slate-300" />
                        <span class="pointer-events-none absolute inset-y-0 right-4 inline-flex items-center text-slate-400">🔍</span>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <select class="h-12 rounded-3xl border border-slate-200 bg-white px-4 text-sm text-slate-700 shadow-sm outline-none focus:border-slate-300">
                            <option>All Status</option>
                            <option>Open</option>
                            <option>In Progress</option>
                            <option>Scheduled</option>
                            <option>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Export
                    </button>
                    <a href="{{ route('cases.create') }}" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        + New Case
                    </a>
                </div>
            </div>
        @endif

        @if(Auth::user()->isLawyer())
            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm overflow-x-auto">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">My Cases</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Case list</h2>
                    </div>
                    <div class="text-sm text-slate-500">Showing {{ $caseGroups->count() }} clients</div>
                </div>

                <table class="mt-6 min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Case No.</th>
                            <th class="px-4 py-3 font-semibold">Case Title</th>
                            <th class="px-4 py-3 font-semibold">Client</th>
                            <th class="px-4 py-3 font-semibold">Practice Area</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Lawyer</th>
                            <th class="px-4 py-3 font-semibold">Updated</th>
                            <th class="px-4 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($caseGroups as $group)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4 font-semibold text-slate-900">{{ $group->latest_case->case_number }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $group->latest_case->title }}<span class="text-xs text-slate-500 ml-2">({{ $group->case_count }} cases)</span></td>
                                <td class="px-4 py-4 text-slate-600">{{ $group->client->full_name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $group->case_type_label }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClass = 'bg-slate-100 text-slate-700';
                                        if ($group->status_label === 'Open') $statusClass = 'bg-blue-100 text-blue-700';
                                        elseif ($group->status_label === 'In Progress') $statusClass = 'bg-orange-100 text-orange-700';
                                        elseif ($group->status_label === 'Scheduled') $statusClass = 'bg-indigo-100 text-indigo-700';
                                        elseif ($group->status_label === 'Closed') $statusClass = 'bg-emerald-100 text-emerald-700';
                                        elseif ($group->status_label === 'Mixed') $statusClass = 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] {{ $statusClass }}">
                                        {{ $group->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $group->lawyer->name ?? '—' }}</td>
                                <td class="px-4 py-4 text-slate-500">{{ \Carbon\Carbon::parse($group->latest_updated_at)->format('M d, Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('clients.show', $group->client) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                            View client
                                        </a>
                                        <a href="{{ route('cases.show', $group->latest_case) }}" class="inline-flex items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                            Manage case
                                        </a>
                                        @can('delete', $group->latest_case)
                                            <form action="{{ route('cases.destroy', $group->latest_case) }}" method="POST" onsubmit="return confirm('Delete this case? This cannot be undone.')" class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No clients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        @elseif(Auth::user()->isAdmin())
            <div class="grid gap-6 xl:grid-cols-[1.8fr_0.95fr]">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm overflow-x-auto">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">All Cases</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Case list</h2>
                        </div>
                        <div class="text-sm text-slate-500">Showing {{ $cases->count() }} cases</div>
                    </div>

                    <table class="mt-6 min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Case No.</th>
                                <th class="px-4 py-3 font-semibold">Case Title</th>
                                <th class="px-4 py-3 font-semibold">Client</th>
                                <th class="px-4 py-3 font-semibold">Practice Area</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Lawyer</th>
                                <th class="px-4 py-3 font-semibold">Updated</th>
                                <th class="px-4 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($cases as $case)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4 font-semibold text-slate-900">{{ $case->case_number }}</td>
                                    <td class="px-4 py-4 text-slate-700">{{ $case->title }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $case->client->full_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $case->case_type }}</td>
                                    <td class="px-4 py-4">
                                        @php
                                            $statusClass = 'bg-slate-100 text-slate-700';
                                            if ($case->status === 'Open') $statusClass = 'bg-blue-100 text-blue-700';
                                            elseif ($case->status === 'In Progress') $statusClass = 'bg-orange-100 text-orange-700';
                                            elseif ($case->status === 'Scheduled') $statusClass = 'bg-indigo-100 text-indigo-700';
                                            elseif ($case->status === 'Closed') $statusClass = 'bg-emerald-100 text-emerald-700';
                                        @endphp
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] {{ $statusClass }}">
                                            {{ $case->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">{{ $case->lawyer->name ?? '—' }}</td>
                                    <td class="px-4 py-4 text-slate-500">{{ $case->updated_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('cases.show', $case) }}" class="inline-flex items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                                View
                                            </a>
                                            @can('update', $case)
                                                <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center justify-center rounded-2xl border border-purple-100 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100">
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('delete', $case)
                                                <form action="{{ route('cases.destroy', $case) }}" method="POST" onsubmit="return confirm('Delete this case? This cannot be undone.')" class="inline-flex">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No cases found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>

                <aside class="space-y-6">
                    <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Case Status Overview</p>
                                <h3 class="mt-2 text-xl font-semibold text-slate-900">Status breakdown</h3>
                            </div>
                        </div>
                        <div class="mt-6 grid gap-6">
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                <div class="relative h-52 w-full">
                                    <canvas id="caseStatusChart"></canvas>
                                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-3xl font-semibold text-slate-900">{{ $totalCases }}</span>
                                        <span class="text-sm text-slate-500">Total cases</span>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                @foreach(['Open', 'In Progress', 'Scheduled', 'Closed', 'Pending', 'On Hold'] as $status)
                                    <div class="flex items-center justify-between rounded-3xl border border-slate-100 bg-white px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="h-3.5 w-3.5 rounded-full" style="background: {{ match ($status) {
                                                'Open' => '#3b82f6',
                                                'In Progress' => '#f97316',
                                                'Scheduled' => '#8b5cf6',
                                                'Closed' => '#10b981',
                                                'Pending' => '#f59e0b',
                                                'On Hold' => '#94a3b8',
                                                default => '#cbd5e1',
                                            } }}"></span>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">{{ $status }}</p>
                                                <p class="text-xs text-slate-500">{{ $statusCounts[$status] ?? 0 }} cases</p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-700">{{ $statusCounts[$status] ?? 0 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Case Types</p>
                        <h3 class="mt-2 text-xl font-semibold text-slate-900">Top categories</h3>
                        <div class="mt-6 space-y-3">
                            @foreach($caseTypeCounts as $type => $count)
                                <div class="flex items-center justify-between gap-3 rounded-3xl border border-slate-100 bg-slate-50 px-4 py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $type }}</p>
                                        <p class="text-xs text-slate-500">{{ $count }} cases</p>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Quick Actions</p>
                        <h3 class="mt-2 text-xl font-semibold text-slate-900">Manage cases</h3>
                        <div class="mt-6 space-y-3">
                            @if(Auth::user()->isLawyer())
                                <a href="{{ route('cases.create') }}" class="block rounded-3xl border border-slate-200 bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Create New Case</a>
                            @endif
                            <a href="{{ route('cases.index') }}" class="block rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">View All Cases</a>
                            <button class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50">Export Case Report</button>
                        </div>
                    </section>
                </aside>
            </div>
        @else
            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm overflow-x-auto">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">All Cases</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Case list</h2>
                    </div>
                    <div class="text-sm text-slate-500">Showing {{ $cases->count() }} cases</div>
                </div>

                <table class="mt-6 min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Case No.</th>
                            <th class="px-4 py-3 font-semibold">Case Title</th>
                            <th class="px-4 py-3 font-semibold">Client</th>
                            <th class="px-4 py-3 font-semibold">Practice Area</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Lawyer</th>
                            <th class="px-4 py-3 font-semibold">Updated</th>
                            <th class="px-4 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($cases as $case)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4 font-semibold text-slate-900">{{ $case->case_number }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $case->title }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $case->client->full_name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-slate-600">{{ $case->case_type }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClass = 'bg-slate-100 text-slate-700';
                                        if ($case->status === 'Open') $statusClass = 'bg-blue-100 text-blue-700';
                                        elseif ($case->status === 'In Progress') $statusClass = 'bg-orange-100 text-orange-700';
                                        elseif ($case->status === 'Scheduled') $statusClass = 'bg-indigo-100 text-indigo-700';
                                        elseif ($case->status === 'Closed') $statusClass = 'bg-emerald-100 text-emerald-700';
                                    @endphp
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] {{ $statusClass }}">
                                        {{ $case->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $case->lawyer->name ?? '—' }}</td>
                                <td class="px-4 py-4 text-slate-500">{{ $case->updated_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('cases.show', $case) }}" class="inline-flex items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                            View
                                        </a>
                                        @can('update', $case)
                                            <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center justify-center rounded-2xl border border-purple-100 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('delete', $case)
                                            <form action="{{ route('cases.destroy', $case) }}" method="POST" onsubmit="return confirm('Delete this case? This cannot be undone.')" class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-slate-500">No cases found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('caseStatusChart');
            if (!ctx) return;

            const labels = ['Open', 'In Progress', 'Scheduled', 'Closed', 'Pending', 'On Hold'];
            const data = [
                {{ $statusCounts['Open'] }},
                {{ $statusCounts['In Progress'] }},
                {{ $statusCounts['Scheduled'] }},
                {{ $statusCounts['Closed'] }},
                {{ $statusCounts['Pending'] }},
                {{ $statusCounts['On Hold'] }}
            ];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#3b82f6', '#f97316', '#8b5cf6', '#10b981', '#f59e0b', '#94a3b8'],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                                    const value = context.parsed;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        });
    </script>
</x-app-layout>
