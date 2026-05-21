<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="width: 100%;">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-2 w-full" style="border: 1px solid #ddd; padding: 12px; border-radius: 8px; font-size: 14px;" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-2 w-full"
                            style="border: 1px solid #ddd; padding: 12px; border-radius: 8px; font-size: 14px;"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div style="margin-bottom: 25px; display: flex; align-items: center;">
            <label for="remember_me" style="display: flex; align-items: center; cursor: pointer;">
                <input id="remember_me" type="checkbox" style="width: 18px; height: 18px; cursor: pointer; accent-color: #009999;" name="remember">
                <span style="margin-left: 8px; font-size: 14px; color: #666;">{{ __('Keep me signed in') }}</span>
            </label>
        </div>

        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            @if (Route::has('password.request'))
                <a style="font-size: 14px; color: #009999; text-decoration: none; transition: all 0.3s ease;" href="{{ route('password.request') }}"
                   onmouseover="this.style.color='#006666'" onmouseout="this.style.color='#009999'">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <button type="submit" style="width: 100%; margin-top: 25px; padding: 14px; background: linear-gradient(135deg, #009999 0%, #006666 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 153, 153, 0.3);" onmouseover="this.style.boxShadow='0 6px 20px rgba(0, 153, 153, 0.5)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0, 153, 153, 0.3)'">
            {{ __('Sign In') }}
        </button>
    </form>
</x-guest-layout>
