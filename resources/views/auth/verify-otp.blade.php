<x-guest-layout>
    <h2 class="text-lg font-bold mb-4 text-center">Enter OTP</h2>

    <form method="POST" action="{{ route('otp.check') }}">
        @csrf

        <div>
            <x-input-label for="otp_code" :value="__('OTP Code (valid for 60 seconds)')" />
            <x-text-input id="otp_code" class="block mt-1 w-full" type="text" name="otp_code" required autofocus />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div class="flex justify-center mt-6">
            <x-primary-button>
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>