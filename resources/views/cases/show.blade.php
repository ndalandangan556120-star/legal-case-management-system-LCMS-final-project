<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Case Details</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $case->title }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">Review the case summary, assigned lawyer, client, hearings, and documents.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('cases.index') }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm hover:bg-slate-50">Back to cases</a>
                @if($case->client)
                    <a href="{{ route('cases.create', ['client_id' => $case->client->id]) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-100">Add another case</a>
                @endif
                @can('update', $case)
                    <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center justify-center rounded-3xl border border-purple-100 bg-purple-50 px-5 py-3 text-sm font-semibold text-purple-700 hover:bg-purple-100">Edit case</a>
                @endcan
                @can('delete', $case)
                    <form action="{{ route('cases.destroy', $case) }}" method="POST" onsubmit="return confirm('Delete this case? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-3xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 hover:bg-rose-100">Delete case</button>
                    </form>
                @endcan
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.7fr_0.95fr]">
            <div class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Case Number</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $case->case_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                            <span class="mt-2 inline-flex rounded-full px-3 py-1 text-sm font-semibold uppercase tracking-[0.16em] text-slate-700 {{ $case->status === 'Open' ? 'bg-blue-100' : ($case->status === 'In Progress' ? 'bg-orange-100' : ($case->status === 'Scheduled' ? 'bg-indigo-100' : ($case->status === 'Closed' ? 'bg-emerald-100' : 'bg-slate-100'))) }}">
                                {{ $case->status }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Practice Area</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $case->case_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Incident Date</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ \Carbon\Carbon::parse($case->incident_date)->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Description</p>
                        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $case->description }}</p>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Client</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $case->client->full_name ?? $case->client->name ?? 'N/A' }}</p>
                            @if($case->client)
                                <p class="mt-2 text-sm text-slate-500">{{ $case->client->email ?? 'No email available' }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Assigned Lawyer</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $case->lawyer->name ?? 'Unassigned' }}</p>
                            <p class="mt-2 text-sm text-slate-500">{{ $case->lawyer->email ?? '' }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Hearings</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Scheduled sessions</h3>
                        </div>
                        <span class="text-sm font-semibold text-slate-500">{{ $case->hearings->count() }} total</span>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse($case->hearings as $hearing)
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 px-4 py-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $hearing->hearing_type ?? 'General Hearing' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y') }}</p>
                                        <p class="mt-1 text-xs text-slate-600">📍 {{ $hearing->location ?? 'Location TBD' }}</p>
                                    </div>
                                    <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-700 border border-slate-200">{{ $hearing->status ?? 'Pending' }}</span>
                                </div>
                                @if($hearing->judge)
                                    <p class="mt-3 text-sm text-slate-600"><strong>Judge:</strong> {{ $hearing->judge }}</p>
                                @endif
                                @if($hearing->notes)
                                    <p class="mt-3 text-sm text-slate-600"><strong>Notes:</strong> {{ $hearing->notes }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    @can('update', $hearing)
                                        <a href="{{ route('hearings.edit', $hearing) }}" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-100">Edit</a>
                                    @endcan
                                    @can('delete', $hearing)
                                        <form action="{{ route('hearings.destroy', $hearing) }}" method="POST" onsubmit="return confirm('Delete this hearing session?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-100">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No hearings scheduled for this case.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Case Timeline</p>
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Recent updates</h3>
                    <div class="mt-6 space-y-4">
                        <div class="space-y-3">
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">Last updated</p>
                                <p class="mt-2 text-sm text-slate-500">{{ $case->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="rounded-3xl border border-slate-100 bg-slate-50 px-4 py-4">
                                <p class="text-sm font-semibold text-slate-900">Created on</p>
                                <p class="mt-2 text-sm text-slate-500">{{ $case->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Documents</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Case files</h3>
                        </div>
                        <span class="text-sm font-semibold text-slate-500">{{ $case->documents->count() }}</span>
                    </div>
                    <div class="mt-6 space-y-3">
                        @forelse($case->documents as $document)
                            <div class="flex items-center justify-between gap-3 rounded-3xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $document->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('documents.download', $document) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Download</a>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No documents uploaded yet.</p>
                        @endforelse
                    </div>
                </section>

                @can('create', App\Models\Document::class)
                    <section class="rounded-3xl border border-slate-100 bg-slate-50 p-6 shadow-sm">
                        <div class="mb-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Evidence</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Upload evidence</h3>
                        </div>
                        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="case_id" value="{{ $case->id }}">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Document name</label>
                                <input type="text" name="name" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Upload file</label>
                                <input type="file" name="document" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900" required>
                            </div>
                            <button type="submit" class="w-full rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Upload Evidence</button>
                        </form>
                    </section>
                @endcan

                @can('create', App\Models\Hearing::class)
                    <section class="rounded-3xl border border-slate-100 bg-slate-50 p-6 shadow-sm">
                        <div class="mb-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Hearings</p>
                            <h3 class="mt-2 text-xl font-semibold text-slate-900">Hearing schedule</h3>
                        </div>
                        <form action="{{ route('hearings.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="case_id" value="{{ $case->id }}">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Case</label>
                                <input type="text" value="{{ $case->case_number }} - {{ $case->title }}" class="w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Date & time</label>
                                <input type="datetime-local" name="hearing_date" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Location</label>
                                <input type="text" name="location" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                                <select name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900" required>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                                <textarea name="notes" rows="3" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-slate-900 focus:ring-slate-900"></textarea>
                            </div>
                            <button type="submit" class="w-full rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">Schedule Hearing</button>
                        </form>
                    </section>
                @endcan
            </aside>
        </div>

    </div>
</x-app-layout>
