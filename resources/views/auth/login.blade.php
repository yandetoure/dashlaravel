<x-guest-layout>

    <!DOCTYPE html>
    <html lang="fr">
    <head>
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KZSXDK2G');</script>
            <!-- End Google Tag Manager -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dakar Transport - Services vers AIBD</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
</head>
<body>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KZSXDK2G');</script>
            <!-- End Google Tag Manager -->

    <!-- Container principal avec dégradé de fond -->
    {{-- <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-r from-blue-100 to-indigo-100"> --}}
        <!-- Logo ou nom de l'application -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-indigo-700">Mon Application</h1>
        </div>

        <!-- Card contenant le formulaire -->
        <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-lg border border-gray-200">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Connexion</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <x-input-label for="email" :value="__('Email')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input id="email"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-text-input id="password"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                </div>

                <!-- Remember Me -->
                <div class="block mb-5">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me"
                            type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-1 cursor-pointer"
                            name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:text-indigo-800 transition duration-150 underline hover:no-underline"
                           href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié?') }}
                        </a>
                    @endif

                    <x-primary-button class="ml-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium shadow-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Se connecter') }}
                    </x-primary-button>
                </div>

                <!-- Lien d'inscription -->
                <div class="text-center mt-6 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
                            S'inscrire
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} Mon Application. Tous droits réservés.
        </div>
    </div>
</body>
</html>
</x-guest-layout>
