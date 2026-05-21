<x-app-layout>
    @section('page-title', 'Client Profile')

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Client Header -->
        <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900">{{ $client->full_name }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $client->email ?? 'No email' }} | {{ $client->phone ?? 'No phone' }}</p>
                    @if($client->address)
                        <p class="mt-3 text-sm text-slate-600">{{ $client->address }}</p>
                    @endif
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center rounded-full bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-900">Client Profile</span>
                </div>
            </div>
        </section>

        <!-- Legal Cases -->
        <div class="space-y-4">
            @php $firstCase = $client->cases->first(); @endphp
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Legal Cases</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">({{ $client->cases->count() }})</h2>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('cases.create', ['client_id' => $client->id]) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">+ Add Case</a>
                    @if($firstCase)
                        <a href="{{ route('cases.show', $firstCase) }}" class="inline-flex items-center justify-center rounded-3xl border border-blue-100 bg-blue-50 px-5 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100">View case</a>
                        @can('update', $firstCase)
                            <a href="{{ route('cases.edit', $firstCase) }}" class="inline-flex items-center justify-center rounded-3xl border border-purple-100 bg-purple-50 px-5 py-3 text-sm font-semibold text-purple-700 hover:bg-purple-100">Edit case</a>
                        @endcan
                    @endif

                    <form method="GET" action="{{ route('clients.show', $client) }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class="rounded-full border border-slate-200 bg-white px-4 py-2 pl-10 text-sm text-slate-700 outline-none focus:border-slate-300">
                            <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">Search</button>
                    </form>
                </div>
            </div>

            @forelse($client->cases as $case)
                <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $case->case_number }}</p>
                            <h3 class="mt-2 text-2xl font-semibold text-slate-900">{{ $case->title }}</h3>
                            <p class="mt-2 text-sm text-slate-500">Assigned to: {{ optional($case->lawyer)->name ?? 'Unassigned' }}</p>
                        </div>
                        <div class="flex items-start justify-end">
                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 {{ $case->status === 'Open' ? 'bg-blue-100' : ($case->status === 'In Progress' ? 'bg-orange-100' : ($case->status === 'Scheduled' ? 'bg-indigo-100' : ($case->status === 'Closed' ? 'bg-emerald-100' : 'bg-slate-100'))) }}">
                                {{ $case->status }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Case Details</p>
                        <div class="mt-3 grid gap-6 md:grid-cols-2">
                            <div>
                                <p class="text-sm text-slate-600">{{ $case->description }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($case->incident_date)->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-100 bg-white p-6 text-slate-600 shadow-sm">
                    No cases are assigned to this client yet.
                </div>
            @endforelse
        </div>
    </div>

</x-app-layout>
