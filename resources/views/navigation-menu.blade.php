<nav x-data="{ open: false }">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <img src="{{ asset('images/marianna-present-logo.png') }}" class="w-56">
            </div>
        <div class="flex justify-center flex-1">
            <div class="flex flex-1 justify-center">
                <div class="hidden sm:flex sm:space-x-24">
                    <x-nav-link href="{{ route('/') }}" :active="request()->routeIs('/')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('onsale') }}" :active="request()->routeIs('onsale')">
                        {{ __('On Sale') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.index')">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('introduction') }}" :active="request()->routeIs('introduction')">
                        {{ __('Introduction') }}
                    </x-nav-link>
                    @if(Auth::user() && Auth::user()->isAdmin())
                    <x-nav-link href="{{ route('admin') }}" :active="request()->routeIs('admin')">
                        {{ __('Admin') }}
                    </x-nav-link>
                    @endif
                        <x-nav-link href="javascript:void(0);" id="searchToggle">
                            {{ __('Search') }}
                        </x-nav-link>
                        <div id="searchBox" class="hidden px-4">
                            <form action="{{ route('search') }}" method="GET" class="flex items-center space-x-2">
                                <input
                                    type="text"
                                    name="query"
                                    class="border rounded-md px-2 py-1 focus:ring-red-500"
                                    required>
                                <button type="submit" class="text-white bg-red-500 px-3 py-1 rounded-md">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    <script>
                        // Toggle searchbar láthatóságáért felel a script - ha show akkor hideen lesz ha hidden akkor show
                        document.getElementById('searchToggle').addEventListener('click', function () {
                            const searchBox = document.getElementById('searchBox');
                            if (searchBox.classList.contains('hidden')) {
                                searchBox.classList.remove('hidden');
                                setTimeout(() => searchBox.classList.add('show'), 0);
                            } else {
                                searchBox.classList.remove('show');
                                setTimeout(() => searchBox.classList.add('hidden'), 200);
                            }
                        });
                    </script>

                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if (Auth::check())
                    <!-- Settings Dropdown -->
                    <x-nav-link class="me-4" href="{{ route('cart') }}" :active="request()->routeIs('cart')">
                        <i class="fas fa-shopping-cart"></i>
                    </x-nav-link>
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ Auth::user()->name }}

                                            <img src="{{ asset('images/marianna-present.png') }}" alt="Logo" class="h-24 w-auto">
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-gray-200 dark:border-gray-600"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <x-nav-link class="me-4" href="{{ route('cart') }}" :active="request()->routeIs('cart')">
                        <i class="fas fa-shopping-cart"></i>
                    </x-nav-link>
                    <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                        {{ __('Log in') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-nav-link>
                @endif
                </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('/') }}" :active="request()->routeIs('/')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('onsale') }}" :active="request()->routeIs('onsale')">
                {{ __('On Sale') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('categories.index') }}" :active="request()->routeIs('categories.index')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('introduction') }}" :active="request()->routeIs('introduction')">
                {{ __('Indrotuction') }}
            </x-responsive-nav-link>
            @if(Auth::user() && Auth::user()->isAdmin())
            <x-responsive-nav-link href="{{ route('admin') }}" :active="request()->routeIs('admin')">
                {{ __('Admin') }}
            </x-responsive-nav-link>
            @endif
            {{--
            <x-responsive-nav-link href="{{ route('search') }}" :active="request()->routeIs('search')">
                {{ __('Search') }}
            </x-responsive-nav-link>
            --}}
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @if (Auth::check())
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            @else
                <div class="px-4">
                    <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
