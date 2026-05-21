<x-app-layout>
    @section('page-title', 'Client Profile')

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Client</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $client->full_name }}</h1>
                    <p class="mt-3 text-sm text-slate-500">{{ $client->email ?? 'No email provided' }} | {{ $client->phone ?? 'No phone provided' }}</p>
                    <p class="mt-4 max-w-2xl text-sm text-slate-600">{{ $client->address ?? 'No address provided' }}</p>
                </div>
                <div class="flex flex-col items-start gap-3 sm:items-end">
                    <span class="inline-flex rounded-full bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-900">Client Profile</span>
                    <span class="inline-flex rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">Clean client view</span>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.7fr_0.95fr]">
            <section class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Legal Cases</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">{{ $client->cases->count() }} case{{ $client->cases->count() === 1 ? '' : 's' }}</h2>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('cases.create', ['client_id' => $client->id]) }}" class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">+ Add Case</a>
                            @if($client->cases->isNotEmpty())
                                <a href="{{ route('cases.show', $client->cases->first()) }}" class="inline-flex items-center justify-center rounded-3xl border border-blue-100 bg-blue-50 px-5 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100">View case</a>
                            @endif
                        </div>
                    </div>

                    <form method="GET" action="{{ route('clients.show', $client) }}" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class="h-12 w-full rounded-full border border-slate-200 bg-white px-5 pl-12 text-sm text-slate-700 outline-none shadow-sm transition focus:border-slate-300" />
                            <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="h-12 rounded-full bg-slate-900 px-5 text-sm font-semibold text-white hover:bg-slate-800">Search</button>
                        @if(request('search'))
                            <a href="{{ route('clients.show', $client) }}" class="h-12 inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Clear</a>
                        @endif
                    </form>
                </section>

                @forelse($client->cases as $case)
                    <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $case->case_number }}</p>
                                <h3 class="mt-2 text-2xl font-semibold text-slate-900">{{ $case->title }}</h3>
                                <p class="mt-3 text-sm text-slate-500">Assigned to: {{ optional($case->lawyer)->name ?? 'Unassigned' }}</p>
                            </div>
                            <div class="flex flex-col items-start gap-3 sm:items-end">
                                <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 {{ $case->status === 'Open' ? 'bg-blue-100 text-blue-700' : ($case->status === 'In Progress' ? 'bg-orange-100 text-orange-700' : ($case->status === 'Scheduled' ? 'bg-indigo-100 text-indigo-700' : ($case->status === 'Closed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700'))) }}">
                                    {{ $case->status }}
                                </span>
                                <div class="flex flex-wrap gap-2">
                                    @can('update', $case)
                                        <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center justify-center rounded-full border border-purple-100 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100">Edit</a>
                                    @endcan
                                    <a href="{{ route('cases.show', $case) }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100">Open details</a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-6 md:grid-cols-2">
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Case summary</p>
                                <p class="mt-3 text-sm text-slate-700">{{ $case->description ?: 'No description available.' }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Incident date</p>
                                <p class="mt-3 text-sm font-semibold text-slate-900">{{ $case->incident_date ? \Carbon\Carbon::parse($case->incident_date)->format('M d, Y') : 'Unknown' }}</p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4 rounded-3xl border border-slate-100 bg-slate-50 p-5">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Hearings</p>
                                    <p class="mt-1 text-sm text-slate-500">Review scheduled hearings for this case.</p>
                                </div>
                                <span class="text-sm font-semibold text-slate-500">{{ $case->hearings->count() }} total</span>
                            </div>
                            @forelse($case->hearings as $hearing)
                                <div class="mt-4 rounded-3xl border border-slate-100 bg-white p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $hearing->hearing_type ?? 'General Hearing' }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ $hearing->hearing_date ? \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y h:i A') : 'Date unavailable' }}</p>
                                            <p class="mt-1 text-xs text-slate-600">📍 {{ $hearing->location ?? 'Location TBD' }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-700 border border-slate-200">{{ $hearing->status ?? 'Pending' }}</span>
                                    </div>
                                    @if($hearing->notes)
                                        <p class="mt-3 text-sm text-slate-600"><strong>Notes:</strong> {{ $hearing->notes }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="mt-4 text-sm text-slate-500">No hearings scheduled for this case.</p>
                            @endforelse
                        </div>
                    </section>
                @empty
                    <section class="rounded-3xl border border-slate-100 bg-white p-6 text-slate-600 shadow-sm">
                        No cases are assigned to this client yet.
                    </section>
                @endforelse
            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Client snapshot</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Quick overview</h3>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 px-5 py-4">
                            <p class="text-sm text-slate-500">Total cases</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $client->cases->count() }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 px-5 py-4">
                            <p class="text-sm text-slate-500">Open cases</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $client->cases->where('status', 'Open')->count() }}</p>
                        </div>
                        <div class="rounded-3xl border border-slate-100 bg-slate-50 px-5 py-4">
                            <p class="text-sm text-slate-500">Upcoming hearings</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $client->cases->flatMap->hearings->where('hearing_date', '>=', now())->count() }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Actions</p>
                    <div class="mt-6 flex flex-col gap-3">
                        <a href="{{ route('cases.create', ['client_id' => $client->id]) }}" class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Create new case</a>
                        <a href="{{ route('clients.index') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">All clients</a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
