<x-app-layout>
    <div class="space-y-8">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        <!-- Client Header -->
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-navy">{{ $client->full_name }}</h1>
                <p class="text-gray-500 mt-1">{{ $client->email }} | {{ $client->phone }}</p>
                <p class="text-gray-600 mt-4 max-w-md">{{ $client->address }}</p>
                <!-- Debug info removed -->
            </div>
            <div class="bg-blue-50 text-navy px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider">
                Client Profile
            </div>
        </div>

        <!-- Cases Section -->
        <div class="space-y-4">
            @php $firstCase = $client->cases->first(); @endphp
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-bold text-gray-800">Legal Cases ({{ $client->cases->count() }})</h2>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <a href="{{ route('cases.create', ['client_id' => $client->id]) }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">+ Add Case</a>
                    @if($firstCase)
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('cases.show', $firstCase) }}" class="inline-flex items-center justify-center rounded-2xl border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">View case</a>
                            @can('update', $firstCase)
                                <a href="{{ route('cases.edit', $firstCase) }}" class="inline-flex items-center justify-center rounded-2xl border border-purple-100 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100">Edit case</a>
                            @endcan
                        </div>
                    @endif
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('clients.show', $client) }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cases..." class="rounded-2xl border border-slate-200 bg-white px-4 py-2 pl-10 text-sm text-slate-700 outline-none focus:border-slate-300">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Search</button>
                    @if(request('search'))
                        <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Clear</a>
                    @endif
                </form>
            </div>
            
            <!-- Debug info removed -->

            @foreach($client->cases as $case)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <span class="text-xs font-bold text-navy uppercase tracking-widest">{{ $case->case_number }}</span>
                            <h3 class="text-xl font-bold text-gray-800">{{ $case->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Assigned to: {{ $case->lawyer->name }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 justify-start md:justify-end">
                            @php
                                $statusClass = 'bg-gray-100 text-gray-700';
                                if ($case->status === 'Open') $statusClass = 'bg-blue-100 text-blue-700';
                                elseif ($case->status === 'In Progress') $statusClass = 'bg-orange-100 text-orange-700';
                                elseif ($case->status === 'Scheduled') $statusClass = 'bg-indigo-100 text-indigo-700';
                                elseif ($case->status === 'Closed') $statusClass = 'bg-green-100 text-green-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                                {{ $case->status }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Case Details -->
                    <div class="p-6 bg-gray-50 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-400 uppercase mb-3">Case Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">What Happened (Description)</p>
                                <p class="text-sm text-gray-800">{{ $case->description }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">When Incident Occurred</p>
                                <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($case->incident_date)->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white border-b border-gray-100 rounded-b-2xl shadow-sm mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-sm font-bold text-gray-400 uppercase">Hearings</h4>
                                <p class="text-sm text-gray-600">Review scheduled hearings for this case.</p>
                            </div>
                            <span class="text-sm font-semibold text-slate-500">{{ $case->hearings->count() }} total</span>
                        </div>

                        <div class="space-y-4">
                            @forelse($case->hearings as $hearing)
                                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $hearing->hearing_type ?? 'General Hearing' }}</p>
                                            <p class="mt-1 text-sm text-slate-500">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M d, Y h:i A') }}</p>
                                            <p class="mt-1 text-xs text-slate-600">📍 {{ $hearing->location ?? 'Location TBD' }}</p>
                                        </div>
                                        <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-slate-700 border border-slate-200">{{ $hearing->status ?? 'Pending' }}</span>
                                    </div>

                                    @if($hearing->notes)
                                        <p class="mt-3 text-sm text-slate-600"><strong>Notes:</strong> {{ $hearing->notes }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">No hearings scheduled for this case.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Documents / Evidence - View Only -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Evidence / Attachments</h4>
                            
                            <!-- Document List -->
                            <div class="space-y-2">
                                @forelse($case->documents as $doc)
                                    <a href="{{ route('documents.download', $doc) }}" class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition">
                                        <div class="flex items-center">
                                            <span class="text-xl mr-3">📄</span>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">{{ $doc->name }}</p>
                                                <p class="text-xs text-gray-400 uppercase">{{ $doc->file_type }}</p>
                                            </div>
                                        </div>
                                        <span class="text-navy text-sm">Download</span>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-400 italic">No documents uploaded.</p>
                                @endforelse
                            </div>
                        </div>

                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
