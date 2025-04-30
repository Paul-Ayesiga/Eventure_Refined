<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.head')
</head>

<body class="font-sans antialiased h-full bg-gray-50 dark:bg-gray-900">
    <div x-data="{ mobileMenuOpen: false }">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-800/90 backdrop-blur-lg shadow">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-2xl font-bold text-teal-600 dark:text-teal-400">Eventure</span>
                    </a>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('home')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Home
                        </a>
                        <a href="{{ route('user.events') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('user.events') || request()->routeIs('user.event.*')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Events
                        </a>
                        @auth
                        @endauth
                    </nav>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <flux:dropdown position="bottom" align="end">
                                <flux:profile :name="auth()->user()->name"
                                    :avatar="auth()->user()->userDetail ?->profile_image ? Storage::url(auth()->
                                        user()->userDetail->profile_image) : null"
                                    :initials="auth()->user()->initials()" />

                                <flux:navmenu>
                                    <flux:navmenu.item :href="route('user-dashboard')" icon="layout-dashboard"
                                        :active="request()->routeIs('user-dashboard')">
                                        Dashboard
                                    </flux:navmenu.item>
                                    <flux:navmenu.item :href="route('user.bookings')" icon="ticket"
                                        :active="request()->routeIs('user.bookings')">
                                        My Bookings
                                    </flux:navmenu.item>
                                    <flux:navmenu.item :href="route('usr.settings.profile')" icon="user"
                                        :active="request()->routeIs('usr.settings.profile')">
                                        Profile
                                    </flux:navmenu.item>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <flux:navmenu.item as="button" type="submit"
                                            icon="arrow-right-start-on-rectangle">
                                            Logout
                                        </flux:navmenu.item>
                                    </form>
                                </flux:navmenu>
                            </flux:dropdown>
                        @else
                            <a href="{{ route('login') }}"
                                class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('login')
                                    ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                    : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-5 py-2 rounded-md shadow-sm hover:shadow transition-all duration-200 font-medium">
                                Sign Up
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" x-show="!mobileMenuOpen">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" x-show="mobileMenuOpen" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div class="md:hidden" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2" style="display: none;">
                    <div class="mt-4 space-y-2 pb-3 pt-2">
                        <a href="{{ route('home') }}"
                            class="block py-2 px-3 rounded-md {{ request()->routeIs('home') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                            Home
                        </a>
                        <a href="{{ route('user.events') }}"
                            class="block py-2 px-3 rounded-md {{ request()->routeIs('user.events') || request()->routeIs('user.event.*') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                            Events
                        </a>
                        @auth

                            <a href="{{ route('usr.settings.profile') }}"
                                class="block py-2 px-3 rounded-md {{ request()->routeIs('usr.settings.profile') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block py-2 px-3">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block py-2 px-3 rounded-md {{ request()->routeIs('login') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="block py-2 px-3 rounded-md {{ request()->routeIs('register') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                                Sign Up
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Header Spacer -->
        <div class="h-20"></div>

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Eventure</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Your one-stop platform for discovering and
                            booking
                            amazing events.</p>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-400">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#"
                                class="text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-400">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path
                                        d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                </svg>
                            </a>
                            <a href="#"
                                class="text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-400">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Home</a>
                            </li>
                            <li><a href="{{ route('user.events') }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Events</a>
                            </li>
                            <li><a href="#"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">About
                                    Us</a></li>
                            <li><a href="#"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Categories</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('user.events', ['selectedCategory' => 'Music']) }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Music</a>
                            </li>
                            <li><a href="{{ route('user.events', ['selectedCategory' => 'Sports']) }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Sports</a>
                            </li>
                            <li><a href="{{ route('user.events', ['selectedCategory' => 'Conferences']) }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Conferences</a>
                            </li>
                            <li><a href="{{ route('user.events', ['selectedCategory' => 'Workshops']) }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Workshops</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Contact Us</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">info@eventure.com</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">+1 (555) 123-4567</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">123 Event Street, City, Country</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8 text-center">
                    <p class="text-gray-600 dark:text-gray-400">&copy; {{ date('Y') }} Eventure. All rights
                        reserved.
                    </p>
                </div>
            </div>
        </footer>

        @livewireScripts
        @fluxScripts
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- AI Assistant Component -->
        @if (isset($showAssistant) && $showAssistant)
            <livewire:assistant.chat-assistant />
        @endif

        <script>
            // Listen for toast events and redirects
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('toast', (message, type = 'success', position = 'top-right') => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: position,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: type,
                        title: message
                    });
                });

                // Listen for redirect events
                Livewire.on('redirect-to', (params) => {
                    console.log('Redirecting to:', params.url);
                    window.location.href = params.url;
                });
            });
        </script>

        @stack('scripts')
</body>

</html>
