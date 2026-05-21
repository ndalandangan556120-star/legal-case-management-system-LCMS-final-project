<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Edit Hearing</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $hearing->legalCase->case_number ?? 'Hearing' }}</h1>
                </div>
                <a href="{{ route('hearings.show', $hearing) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-50">Back to hearing</a>
            </div>

            <!-- Case Details Section -->
            <div class="mt-8 rounded-3xl border border-slate-100 bg-slate-50 p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Associated Case</p>
                <div class="mt-4 grid gap-6 lg:grid-cols-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Case Number</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $hearing->legalCase->case_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Title</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $hearing->legalCase->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Client</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $hearing->legalCase->client->full_name ?? $hearing->legalCase->client->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Status</p>
                        <span class="mt-2 inline-flex rounded-full px-3 py-1 text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 {{ $hearing->legalCase->status === 'Open' ? 'bg-blue-100' : ($hearing->legalCase->status === 'In Progress' ? 'bg-orange-100' : ($hearing->legalCase->status === 'Scheduled' ? 'bg-indigo-100' : ($hearing->legalCase->status === 'Closed' ? 'bg-emerald-100' : 'bg-slate-100'))) }}">
                            {{ $hearing->legalCase->status }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('hearings.update', $hearing) }}" method="POST" class="mt-8 grid gap-4">
                @csrf
                @method('PUT')

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Case</label>
                        <select name="case_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none">
                            @foreach($cases as $case)
                                <option value="{{ $case->id }}" {{ old('case_id', $hearing->case_id) == $case->id ? 'selected' : '' }}>{{ $case->case_number }} - {{ $case->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date & Time</label>
                        <input type="datetime-local" name="hearing_date" value="{{ old('hearing_date', \Carbon\Carbon::parse($hearing->hearing_date)->format('Y-m-d\TH:i')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" required>
                    </div>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location', $hearing->location) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" required>
                            <option value="Scheduled" {{ old('status', $hearing->status) === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="Completed" {{ old('status', $hearing->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Cancelled" {{ old('status', $hearing->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Notes</label>
                    <textarea name="notes" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" placeholder="Add any additional notes for this hearing">{{ old('notes', $hearing->notes) }}</textarea>
                </div>

                <div class="mt-8 border-t border-slate-200 pt-6">
                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">Update Hearing</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
