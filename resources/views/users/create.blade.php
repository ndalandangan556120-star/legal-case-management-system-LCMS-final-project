<x-app-layout>
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">New user</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">Create account</h1>
                </div>
                <a href="{{ route('users.index') }}" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-900 hover:bg-slate-50">Back to users</a>
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                    @error('name') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                    @error('email') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                        @error('password') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Confirm password</label>
                        <input type="password" name="password_confirmation" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Role</label>
                    <select name="role" required class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-300 focus:outline-none">
                        <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Lawyer" {{ old('role') === 'Lawyer' ? 'selected' : '' }}>Lawyer</option>
                        <option value="Client" {{ old('role') === 'Client' ? 'selected' : '' }}>Client</option>
                    </select>
                    @error('role') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6">
                    <button type="submit" class="inline-flex h-12 w-full items-center justify-center rounded-2xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">Create user account</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
