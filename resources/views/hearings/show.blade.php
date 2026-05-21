<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        @if(session('success') || session('error'))
            <div class="rounded-3xl border px-5 py-4 text-sm shadow-sm {{ session('success') ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif
        <!-- Header Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Hearing Details</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $hearing->legalCase->case_number ?? 'Case Hearing' }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $hearing->legalCase->title ?? 'No case title' }}</p>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('hearings.index') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Back to Hearings</a>
                    @can('update', $hearing)
                        <a href="{{ route('hearings.edit', $hearing) }}" class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Edit Hearing</a>
                    @endcan
                    @can('delete', $hearing)
                        <form action="{{ route('hearings.destroy', $hearing) }}" method="POST" onsubmit="return confirm('Delete this hearing session?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-3xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 hover:bg-rose-100">Delete hearing</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Case Details Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Case Details</p>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-3">Case Number</h3>
                    <p class="text-lg font-semibold text-slate-900">{{ $hearing->legalCase->case_number ?? 'No case number' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-3">Case Title</h3>
                    <p class="text-lg font-semibold text-slate-900">{{ $hearing->legalCase->title ?? 'No case title' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-3">What Happened (Description)</h3>
                    <p class="text-sm text-slate-700">{{ $hearing->legalCase->description ?? 'No description available.' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-3">Case Status</h3>
                    <p class="text-sm font-semibold text-slate-900">{{ $hearing->legalCase->status ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>

        <!-- Hearing Schedule Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Hearing Schedule</p>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Scheduled Date & Time</p>
                    <p class="mt-3 text-xl font-semibold text-slate-900">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('h:i A') }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Location</p>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $hearing->location }}</p>
                </div>
            </div>
        </div>

        <!-- Status & Client Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Hearing Status & Client</p>
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Hearing Status</p>
                    <span class="mt-3 inline-flex rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-[0.16em] {{ $hearing->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($hearing->status === 'Cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-sky-100 text-sky-700') }}">
                        {{ $hearing->status }}
                    </span>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Client</p>
                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $hearing->legalCase->client->full_name ?? 'Unknown client' }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $hearing->legalCase->client->email ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500 mb-4">Hearing Notes</p>
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                <p class="text-sm text-slate-700 leading-relaxed">{{ $hearing->notes ?? 'No additional notes.' }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
