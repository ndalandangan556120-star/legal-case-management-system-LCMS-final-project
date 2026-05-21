<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Greeting Section -->
        @auth
        <div class="mb-8 rounded-lg bg-gradient-to-r from-slate-50 to-slate-100 border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-slate-900">
                            Good {{ date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name ?? 'User' }} 
                            <span class="text-2xl">{{ date('H') < 12 ? '👋' : (date('H') < 17 ? '🌤️' : '🌙') }}</span>
                        </h1>
                    </div>
                    <p class="mt-1 text-sm text-slate-600">Here's what's happening with your cases today.</p>
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm text-slate-500 text-right">
                        <span class="font-semibold text-slate-900">{{ date('l, F j') }}</span><br>
                        <span>{{ date('g:i A') }}</span>
                    </p>
                </div>
            </div>
        </div>
        @endauth

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Cases</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $totalCases }}</p>
                    </div>
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7M8 3h8l1.5 4H6.5L8 3z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500">All case records</p>
                <p class="mt-4 text-xs font-semibold text-emerald-600">↑ 12% from last month</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Active Cases</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $activeCases }}</p>
                    </div>
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500">Ongoing case work</p>
                <p class="mt-4 text-xs font-semibold text-emerald-600">↑ 8% from last month</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Hearings Today</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $hearingsToday }}</p>
                    </div>
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-purple-100 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M4 21h16a2 2 0 0 0 2-2V8H2v11a2 2 0 0 0 2 2z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500">Scheduled for today</p>
                <p class="mt-4 text-xs font-semibold text-slate-600">↓ 5% from yesterday</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Clients</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $totalClients }}</p>
                    </div>
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-orange-100 text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500">Registered clients</p>
                <p class="mt-4 text-xs font-semibold text-amber-600">↑ 7% from last month</p>
            </div>
        </div>

        @if(!isset($clientCases))
            <!-- Top Row: Case Status Overview & Upcoming Hearings -->
            <div class="grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-6 mb-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Case Status Overview</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Current status breakdown</h2>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600">
                            <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                            Updated just now
                        </div>
                    </div>
                    <div class="mt-8 grid gap-6 lg:grid-cols-[240px_minmax(0,1fr)] lg:items-center">
                        <div class="relative h-[240px] w-full">
                            <canvas id="caseStatusChart"></canvas>
                            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                                <span class="text-3xl font-semibold text-slate-900">{{ $totalCases }}</span>
                                <span class="text-sm text-slate-400">Total cases</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @php
                                $statusLegend = [
                                    'Open' => '#3b82f6',
                                    'In Progress' => '#f97316',
                                    'Pending' => '#8b5cf6',
                                    'Closed' => '#10b981',
                                    'Disposed' => '#f59e0b',
                                    'On Hold' => '#94a3b8',
                                ];
                            @endphp
                            @foreach($statusLegend as $label => $color)
                                @php $value = $caseStatusCounts[$label] ?? 0; @endphp
                                <div class="flex items-center justify-between gap-3 rounded-3xl border border-slate-100 bg-slate-50 px-4 py-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="h-3.5 w-3.5 rounded-full" style="background: {{ $color }}"></span>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-900">{{ $label }}</p>
                                            @php $percentage = $totalCases ? number_format(($value / $totalCases) * 100, 1) : 0; @endphp
                                            <p class="text-xs text-slate-500">{{ $value }} cases · {{ $percentage }}%</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3 mb-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Upcoming Hearings</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">Next hearings</h3>
                        </div>
                        <a href="{{ route('hearings.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap">View Calendar →</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($upcomingHearings as $index => $hearing)
                            <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4 hover:bg-white transition">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-2xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('H:i') }}</p>
                                            <p class="text-xs text-slate-500 uppercase tracking-wider">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('A') }}</p>
                                        </div>
                                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13h-1v6l5.25 3.15.75-1.23-5-2.92z"/></svg>
                                            Courtroom {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-900">{{ $hearing->legalCase->title ?? 'Untitled Case' }}</h4>
                                        <p class="text-xs text-slate-500">{{ $hearing->legalCase->case_number ?? 'Case' }} · {{ $hearing->legalCase->client->name ?? 'Unknown' }}</p>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-6">No upcoming hearings scheduled.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Bottom Row: Recent Cases & Tasks -->
            <div class="grid grid-cols-1 lg:grid-cols-[1.6fr_1fr] gap-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm overflow-x-auto">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Recent Cases</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">Latest case activity</h3>
                        </div>
                        <a href="{{ route('cases.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 whitespace-nowrap">View all →</a>
                    </div>
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-slate-600">CASE NO.</th>
                                <th class="px-4 py-3 font-semibold text-slate-600">CASE TITLE</th>
                                <th class="px-4 py-3 font-semibold text-slate-600">CLIENT</th>
                                <th class="px-4 py-3 font-semibold text-slate-600">STATUS</th>
                                <th class="px-4 py-3 font-semibold text-slate-600">UPDATED</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($recentCases as $case)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4 text-slate-700 font-semibold">{{ $case->case_number }}</td>
                                    <td class="px-4 py-4 text-slate-900 font-medium">{{ \Illuminate\Support\Str::limit($case->title, 35) }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $case->client->name ?? '—' }}</td>
                                    <td class="px-4 py-4">
                                        @php
                                            $statusColors = [
                                                'Open' => 'bg-blue-50 text-blue-700',
                                                'In Progress' => 'bg-orange-50 text-orange-700',
                                                'Pending' => 'bg-purple-50 text-purple-700',
                                                'Closed' => 'bg-green-50 text-green-700',
                                                'Disposed' => 'bg-amber-50 text-amber-700',
                                                'On Hold' => 'bg-slate-100 text-slate-700'
                                            ];
                                        @endphp
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $statusColors[$case->status] ?? 'bg-slate-100 text-slate-700' }}">{{ $case->status }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-500">{{ $case->updated_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">No recent cases available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tasks Overview</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">Current workload</h3>
                        </div>
                        <a href="#" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">View All Tasks</a>
                    </div>
                    <div class="space-y-4">
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-600">Pending Tasks</p>
                                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $tasks['pending'] }}</p>
                                </div>
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-orange-100 text-orange-600">⏳</div>
                            </div>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-600">Tasks Due Today</p>
                                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $tasks['due_today'] }}</p>
                                </div>
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-rose-100 text-rose-600">📋</div>
                            </div>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-600">Completed Tasks</p>
                                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $tasks['completed'] }}</p>
                                </div>
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-emerald-100 text-emerald-600">✅</div>
                            </div>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-600">Overdue Tasks</p>
                                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $tasks['overdue'] }}</p>
                                </div>
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-3xl bg-red-100 text-red-600">⚠️</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
    </div>

    @if(!isset($clientCases))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const chartLabels = ['Open', 'In Progress', 'Pending', 'Closed', 'Disposed', 'On Hold'];
                const chartData = [
                    {{ $caseStatusCounts['Open'] ?? 0 }},
                    {{ $caseStatusCounts['In Progress'] ?? 0 }},
                    {{ $caseStatusCounts['Pending'] ?? 0 }},
                    {{ $caseStatusCounts['Closed'] ?? 0 }},
                    {{ $caseStatusCounts['Disposed'] ?? 0 }},
                    {{ $caseStatusCounts['On Hold'] ?? 0 }}
                ];

                new Chart(document.getElementById('caseStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
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
    @endif
</x-app-layout>
