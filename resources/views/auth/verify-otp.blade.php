<x-guest-layout>
    <h2 class="h4 fw-bold mb-3 text-center">Enter OTP</h2>

    <form method="POST" action="{{ route('otp.check') }}">
        @csrf

        <div>
            <x-input-label for="otp_code" :value="__('OTP Code (valid for 60 seconds)')" />
            <x-text-input id="otp_code" class="d-block mt-1 w-100" type="text" name="otp_code" required autofocus />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div class="d-flex justify-content-center mt-4">
            <x-primary-button>
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>