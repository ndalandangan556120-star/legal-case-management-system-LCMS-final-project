<x-app-layout>
    <div class="max-w-2xl mx-auto py-8">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-navy mb-6">Register New Client</h1>
            
            <form action="{{ route('clients.store') }}" method="POST" class="space-y-4 pb-8">
                @csrf

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="rounded-lg bg-emerald-100 border border-emerald-200 p-4 text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="rounded-lg bg-rose-100 border border-rose-200 p-4 text-rose-800">
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-navy focus:ring-navy" 
                        placeholder="e.g. Juan dela Cruz">
                    @error('full_name')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-navy focus:ring-navy" 
                        placeholder="you@example.com"
                        required>
                    @error('email')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-navy focus:ring-navy" 
                        placeholder="+63 9XX XXX XXXX">
                    @error('phone')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="3" 
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-navy focus:ring-navy" 
                        placeholder="Street, Barangay, City, Province"
                        required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="mt-8 border-t border-gray-200 pt-6 flex flex-wrap gap-4 justify-between">
                    <a href="{{ route('clients.index') }}" 
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-white border-2 border-black text-black py-3 px-6 rounded-lg font-bold text-base hover:bg-gray-100 transition text-center shadow-sm">
                        ← Back to Clients
                    </a>
                    <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-slate-900 text-white py-3 px-6 rounded-lg font-bold text-base hover:bg-slate-700 transition border-2 border-slate-900 shadow-sm text-center">
                        ✓ Create Client Profile
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>