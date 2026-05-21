<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-slate-900 mb-6">Edit Case</h1>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cases.update', $case) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Case Number</label>
                        <input type="text" name="case_number" value="{{ old('case_number', $case->case_number) }}" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Incident Date</label>
                        <input type="date" name="incident_date" value="{{ old('incident_date', $case->incident_date) }}" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Case Title</label>
                    <input type="text" name="title" value="{{ old('title', $case->title) }}" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>{{ old('description', $case->description) }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>
                            @foreach(\App\Models\LegalCase::statuses() as $status)
                                <option value="{{ $status }}" {{ old('status', $case->status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <select name="client_id" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $case->client_id) == $client->id ? 'selected' : '' }}>{{ $client->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Lawyer</label>
                        @if(isset($currentLawyer) && $currentLawyer->isLawyer())
                            <input type="hidden" name="lawyer_id" value="{{ $currentLawyer->id }}">
                            <div class="mt-1 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-700">{{ $currentLawyer->name }}</div>
                        @else
                            <select name="lawyer_id" class="w-full rounded-lg border-gray-300 focus:border-slate-900 focus:ring-slate-900" required>
                                @foreach($lawyers as $lawyer)
                                    <option value="{{ $lawyer->id }}" {{ old('lawyer_id', $case->lawyer_id) == $lawyer->id ? 'selected' : '' }}>{{ $lawyer->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
