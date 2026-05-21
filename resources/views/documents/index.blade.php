<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Documents</p>
                <h1 class="mt-2 text-3xl font-semibold text-slate-900">Case attachments</h1>
                <p class="mt-2 text-sm text-slate-500">Download evidence files or case documents from your cases.</p>
            </div>
            <div>
                <a href="{{ url()->previous() }}" class="inline-flex items-center rounded-3xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Back</a>
            </div>
        </div>

        <div class="grid gap-6">
            @if($documents->isEmpty())
                <div class="rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-sm">
                    <p class="text-lg font-semibold text-slate-900">No documents available yet.</p>
                    <p class="mt-2 text-sm text-slate-500">Upload evidence from the client case page or check back later.</p>
                </div>
            @else
                @foreach($documents as $doc)
                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-sm sm:flex sm:items-center sm:justify-between">
                        <div class="space-y-2">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $doc->legalCase->case_number ?? 'Case' }}</p>
                            <h2 class="text-xl font-semibold text-slate-900">{{ $doc->name }}</h2>
                            <p class="text-sm text-slate-600">Case title: {{ $doc->legalCase->title ?? 'Unknown case' }}</p>
                            <p class="text-sm text-slate-500">Client: {{ $doc->legalCase->client->full_name ?? 'Unknown client' }}</p>
                        </div>

                        <div class="mt-4 flex flex-col gap-3 sm:mt-0 sm:items-end">
                            <span class="text-sm text-slate-500">Uploaded {{ $doc->created_at->diffForHumans() }}</span>
                            <a href="{{ route('documents.download', $doc) }}" class="inline-flex items-center rounded-3xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Download</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
