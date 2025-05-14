{{-- <x-guest-layout> --}}
    <!-- Include the CSS -->
    <style>
        /* General Styles */
        .auth-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Session Status */
        .session-status {
            margin-bottom: 16px;
            color: #10b981;
            /* Equivalent to text-green-600 in Tailwind */
        }

        /* Input Label */
        .input-label {
            font-size: 0.875rem;
            /* Equivalent to text-sm */
            font-weight: 500;
            /* Equivalent to font-medium */
            color: #4b5563;
            /* Equivalent to text-gray-700 */
        }

        /* Text Input */
        .text-input {
            display: block;
            margin-top: 4px;
            width: 98%;
            padding: 8px;
            border: 1px solid #d1d5db;
            /* Equivalent to border-gray-300 */
            border-radius: 4px;
            /* Equivalent to rounded-md */
            font-size: 1rem;
            line-height: 1.5;
            color: #111827;
            /* Equivalent to text-gray-900 */
        }

        .text-input:focus {
            outline: none;
            border-color: #6366f1;
            /* Equivalent to focus:border-indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            /* Equivalent to focus:ring-indigo-500 */
        }

        /* Input Error */
        .input-error {
            margin-top: 8px;
            color: #ef4444;
            /* Equivalent to text-red-600 */
            font-size: 0.875rem;
            /* Equivalent to text-sm */
        }

        /* Checkbox */
        .checkbox {
            border-radius: 4px;
            /* Equivalent to rounded */
            border: 1px solid #d1d5db;
            /* Equivalent to border-gray-300 */
            accent-color: #4f46e5;
            /* Equivalent to text-indigo-600 */
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            /* Equivalent to shadow-sm */
        }

        .checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            /* Equivalent to focus:ring-indigo-500 */
        }

        /* Remember Me Label */
        .remember-me-label {
            display: inline-flex;
            align-items: center;
        }

        .remember-me-text {
            margin-left: 8px;
            /* Equivalent to ms-2 */
            font-size: 0.875rem;
            /* Equivalent to text-sm */
            color: #4b5563;
            /* Equivalent to text-gray-600 */
        }

        /* Forgot Password Link */
        .forgot-password-link {
            text-decoration: underline;
            font-size: 0.875rem;
            /* Equivalent to text-sm */
            color: #4b5563;
            /* Equivalent to text-gray-600 */
            border-radius: 4px;
            /* Equivalent to rounded-md */
        }

        .forgot-password-link:hover {
            color: #111827;
            /* Equivalent to hover:text-gray-900 */
        }

        .forgot-password-link:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            /* Equivalent to focus:ring-2 focus:ring-indigo-500 */
        }

        /* Primary Button */
        .primary-button {
            margin-left: 12px;
            /* Equivalent to ms-3 */
            padding: 8px 16px;
            background-color: #4f46e5;
            /* Equivalent to bg-indigo-600 */
            color: white;
            border: none;
            border-radius: 4px;
            /* Equivalent to rounded-md */
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
        }

        .primary-button:hover {
            background-color: #4338ca;
            /* Equivalent to hover:bg-indigo-700 */
        }

        .primary-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            /* Equivalent to focus:ring-indigo-500 */
        }

        /* Flex Container for Buttons */
        .button-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 16px;
        }

        /* Block Container */
        .block-container {
            display: block;
            margin-top: 16px;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <!-- Session Status -->
    <x-auth-session-status class="session-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" style="    WIDTH: 400PX;
      MARGIN: AUTO;
      top: 29%;
      position: relative;
      box-shadow: 0px 0px 3px #929292;
      border-radius: 10px;
      padding: 48px;">



        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="input-label" />
            <x-text-input id="email" class="text-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="input-error" />
        </div>

        <!-- Password -->
        <div class="block-container">
            <x-input-label for="password" :value="__('Password')" class="input-label" />
            <x-text-input id="password" class="text-input" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="input-error" />
        </div>

        <!-- Remember Me -->
        <div class="block-container">
            <label for="remember_me" class="remember-me-label">
                <input id="remember_me" type="checkbox" class="checkbox" name="remember">
                <span class="remember-me-text">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Buttons -->
        <div class="button-container">
            @if (Route::has('password.request'))
            <a class="forgot-password-link" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="primary-button">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
{{-- </x-guest-layout> --}}
