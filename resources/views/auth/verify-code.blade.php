<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Please enter the 6-digit verification code sent to your email address') }} 
        <strong class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ session('reset_email') }}</strong>.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.verify-code') }}" id="verify-form">
        @csrf

        <!-- 6-digit input boxes -->
        <div class="flex justify-between gap-2 my-6">
            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required autofocus />
            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
            <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-xl font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required />
        </div>

        <input type="hidden" name="code" id="verification_code" />

        <x-input-error :messages="$errors->get('code')" class="mt-2" />

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('password.request') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Resend Code') }}
            </a>

            <x-primary-button>
                {{ __('Verify Code') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('verification_code');
            const form = document.getElementById('verify-form');

            inputs.forEach((input, index) => {
                // Focus shifting on input
                input.addEventListener('input', (e) => {
                    if (e.target.value.length > 0) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    }
                    updateHiddenValue();
                });

                // Focus shifting on backspace
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value.length === 0) {
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });

                // Handle paste
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').slice(0, 6).trim();
                    if (/^\d+$/.test(pastedData)) {
                        for (let i = 0; i < Math.min(pastedData.length, inputs.length); i++) {
                            inputs[i].value = pastedData[i];
                            if (i < inputs.length - 1) {
                                inputs[i + 1].focus();
                            }
                        }
                        updateHiddenValue();
                    }
                });
            });

            function updateHiddenValue() {
                let code = '';
                inputs.forEach(input => {
                    code += input.value;
                });
                hiddenInput.value = code;
            }

            form.addEventListener('submit', function (e) {
                updateHiddenValue();
                if (hiddenInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Please enter all 6 digits of the verification code.');
                }
            });
        });
    </script>
</x-guest-layout>
