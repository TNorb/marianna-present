<x-app-layout>
    <title>Register</title>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms-and-conditions').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>

                <div class="mt-4">
                    <x-label for="privacy_policy">
                        <div class="flex items-center">
                            <x-checkbox name="privacy_policy" id="privacy_policy" required />
                            <div class="ms-2">
                                {!! __('I agree to the :privacy_policy', [
                                    'privacy_policy' => '<a target="_blank" href="'.route('privacy-policy').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>

                <div class="mt-4">
                    <x-label for="terms_of_purchase">
                        <div class="flex items-center">
                            <x-checkbox name="terms_of_purchase" id="terms_of_purchase" required />
                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_purchase', [
                                    'terms_of_purchase' => '<a target="_blank" href="'.route('terms-of-purchase').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">'.__('Terms of Purchase').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>

                <div class="mt-4">
                    <x-label for="shipping_informations">
                        <div class="flex items-center">
                            <x-checkbox name="shipping_informations" id="shipping_informations" required />
                            <div class="ms-2">
                                {!! __('I agree to the :shipping_informations', [
                                    'shipping_informations' => '<a target="_blank" href="'.route('shipping-informations').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">'.__('Shipping Informations').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>

                <div class="mt-4">
                    <x-label for="consumer_informations">
                        <div class="flex items-center">
                            <x-checkbox name="consumer_informations" id="consumer_informations" required />
                            <div class="ms-2">
                                {!! __('I agree to the :consumer_informations', [
                                    'consumer_informations' => '<a target="_blank" href="'.route('consumer-informations').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">'.__('Consumer Informations').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>

                <div class="mt-4">
                    <x-label for="declaration_of_withdrawal">
                        <div class="flex items-center">
                            <x-checkbox name="declaration_of_withdrawal" id="declaration_of_withdrawal" required />
                            <div class="ms-2">
                                {!! __('I agree to the :declaration_of_withdrawal', [
                                    'declaration_of_withdrawal' => '<a target="_blank" href="'.route('declaration-of-withdrawal').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">'.__('Declaration of Withdrawal').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-app-layout>
