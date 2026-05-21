<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Hearings</p>
                @if(Auth::user()->isLawyer())
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">Lawyer hearing overview</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">View next hearing, calendar, and recent case updates.</p>
                @elseif(Auth::user()->isClient())
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">Client hearing overview</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">View your upcoming hearings and recent hearing activity.</p>
                @else
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">Hearing dashboard</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">View all hearings and recent updates from one clean interface.</p>
                @endif
            </div>
            @if(Auth::user()->isLawyer() || Auth::user()->isAdmin())
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="relative w-full min-w-[260px] sm:w-auto">
                    <form action="{{ route('hearings.index') }}" method="GET" class="relative">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="case_id" value="{{ request('case_id') }}">
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search hearings and cases..."
                            class="h-11 w-full rounded-2xl border border-slate-200 bg-white px-4 pr-12 text-sm text-slate-700 shadow-sm outline-none focus:border-slate-300" />
                        <button type="submit" class="pointer-events-auto absolute inset-y-0 right-0 mr-3 inline-flex items-center text-slate-500">🔍</button>
                    </form>
                </div>
                @if(Auth::user()->isAdmin())
                    <a href="#new-hearing" class="inline-flex h-11 items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                        + New Hearing
                    </a>
                @endif
            </div>
            @endif
        </div>

        @if(session('success') || session('error'))
            <div class="rounded-3xl border px-5 py-4 text-sm shadow-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        @if(Auth::user()->isLawyer() || Auth::user()->isAdmin())
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-5 top-5 text-slate-200 text-4xl">📅</div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Total Hearings</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $totalHearings }}</p>
                <p class="mt-3 text-sm text-slate-500">All hearings scheduled in the system.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-5 top-5 text-slate-200 text-4xl">⏳</div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Upcoming</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $upcomingHearings->count() }}</p>
                <p class="mt-3 text-sm text-slate-500">Hearings scheduled for today and the future.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-5 top-5 text-slate-200 text-4xl">✅</div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Completed</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $completedCount }}</p>
                <p class="mt-3 text-sm text-slate-500">Hearings that have already taken place.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-5 top-5 text-slate-200 text-4xl">🚫</div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Cancelled</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $cancelledCount }}</p>
                <p class="mt-3 text-sm text-slate-500">Hearings that were cancelled or rescheduled.</p>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-5 top-5 text-slate-200 text-4xl">🧭</div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Scheduled</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $scheduledCount }}</p>
                <p class="mt-3 text-sm text-slate-500">Hearings currently in the scheduled pipeline.</p>
            </div>
        </div>
        @endif

        @if(Auth::user()->isLawyer() || Auth::user()->isAdmin())
        <section id="hearings-filter" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Filter hearings</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Refine the list</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('hearings.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50">Reset</a>
                </div>
            </div>
            <form action="{{ route('hearings.index') }}" method="GET" class="grid gap-4 lg:grid-cols-6">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Case number, title or location"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Case</label>
                    <select name="case_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none">
                        <option value="">All cases</option>
                        @foreach($cases as $case)
                            <option value="{{ $case->id }}" {{ request('case_id') == $case->id ? 'selected' : '' }}>{{ $case->case_number }} - {{ $case->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none">
                        <option value="">All statuses</option>
                        <option value="Scheduled" {{ request('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Apply filters</button>
                </div>
            </form>
        </section>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1.9fr_0.95fr]">
            <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm overflow-x-auto">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Upcoming Hearings</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Next hearings</h2>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-3xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Date & Time</th>
                                <th class="px-4 py-3 font-semibold">Case</th>
                                <th class="px-4 py-3 font-semibold">Courtroom</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($upcomingHearings as $hearing)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</p>
                                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-slate-700">{{ $hearing->legalCase->case_number ?? '—' }}<br><span class="text-xs text-slate-500">{{ $hearing->legalCase->title ?? 'Unknown case' }}</span></td>
                                    <td class="px-4 py-4 text-slate-600">Courtroom 1</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $hearing->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($hearing->status === 'Cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-sky-100 text-sky-700') }}">
                                            {{ $hearing->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('hearings.show', $hearing) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200">View</a>
                                            @can('update', $hearing)
                                                <a href="{{ route('hearings.edit', $hearing) }}" class="inline-flex items-center rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">Edit</a>
                                            @endcan
                                            @can('delete', $hearing)
                                                <form action="{{ route('hearings.destroy', $hearing) }}" method="POST" onsubmit="return confirm('Delete this hearing session?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">Delete</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">No upcoming hearings scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            @if(Auth::user()->isLawyer() || Auth::user()->isAdmin())
            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Quick Actions</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">What next?</h3>
                    <div class="mt-6 space-y-3">
                        @if(Auth::user()->isAdmin())
                            <a href="#new-hearing" class="block rounded-3xl border border-slate-200 bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Add New Hearing Details</a>
                        @endif
                        <a href="{{ route('hearings.index') }}" class="block rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">View Calendar</a>
                        <button class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50">Hearing Reports</button>
                        <button class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50">Court Rules & Guidelines</button>
                    </div>
                </section>
            </aside>
            @endif
        </div>

        <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Recent Hearings</p>
                    <h3 class="mt-2 text-2xl font-semibold text-slate-900">Latest updates</h3>
                </div>
                <span class="text-sm text-slate-500">Showing {{ $recentHearings->count() }} most recent hearings</span>
            </div>
            <div class="mt-6 space-y-3">
                @forelse($recentHearings as $hearing)
                    <div class="flex flex-col gap-3 rounded-3xl border border-slate-100 bg-slate-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $hearing->legalCase->title ?? 'Untitled Case' }}</p>
                            <p class="text-sm text-slate-500">{{ $hearing->legalCase->case_number ?? '—' }} · {{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                            <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700">{{ $hearing->status }}</span>
                            <a href="{{ route('hearings.show', $hearing) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100">View</a>
                            @can('update', $hearing)
                                <a href="{{ route('hearings.edit', $hearing) }}" class="inline-flex items-center rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 hover:bg-indigo-100">Edit</a>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No recent hearing activity.</p>
                @endforelse
            </div>
        </section>

        @can('create', App\Models\Hearing::class)
            <section id="new-hearing" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Schedule New Hearing</p>
                        <h3 class="mt-2 text-2xl font-semibold text-slate-900">Add New Hearing Details</h3>
                    </div>
                </div>
                <form action="{{ route('hearings.store') }}" method="POST" class="mt-6 grid gap-4 lg:grid-cols-2">
                    @csrf
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Case</label>
                        <select name="case_id" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none">
                            <option value="">Select a case</option>
                            @foreach($cases as $case)
                                <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>{{ $case->case_number }} - {{ $case->title }}</option>
                            @endforeach
                        </select>
                        @error('case_id')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Date & Time</label>
                        <input type="datetime-local" name="hearing_date" value="{{ old('hearing_date') }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                        @error('hearing_date')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g., Courtroom 1" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                        @error('location')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" required>
                            <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="space-y-3 lg:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700">Notes</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Optional notes" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                    </div>
                    <div class="lg:col-span-2">
                        <button type="submit" class="inline-flex h-12 w-full items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white transition hover:bg-slate-800">Schedule Hearing</button>
                    </div>
                </form>
            </section>
        @endif
    </div>
</x-app-layout>
