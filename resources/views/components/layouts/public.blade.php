<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    @include('partials.head')
</head>

<body class="font-sans antialiased h-full bg-gray-50 dark:bg-gray-900">
    <div x-data="{ mobileMenuOpen: false }">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-800/75 backdrop-blur-lg shadow">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center">
                        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 7h4a2 2 0 012 2v6a2 2 0 01-2 2h-4m-6 0H5a2 2 0 01-2-2V9a2 2 0 012-2h4m6 0v10m-6 0V7'/></svg>">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 dark:text-teal-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7h4a2 2 0 012 2v6a2 2 0 01-2 2h-4m-6 0H5a2 2 0 01-2-2V9a2 2 0 012-2h4m6 0v10m-6 0V7" />
                        </svg> --}}
                        <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#10b2a7" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.4" d="M19.8503 12.9402C19.8503 13.7402 20.5003 14.4002 21.3003 14.4002C21.6803 14.4002 22.0003 14.7102 22.0003 15.0902C22.0003 18.9302 20.8403 20.0902 17.0003 20.0902H11.7503V18.5002C11.7503 18.1102 11.4503 17.7902 11.0703 17.7602V14.9102C11.4503 14.8802 11.7503 14.5602 11.7503 14.1702V9.83018C11.7503 9.44018 11.4503 9.12018 11.0703 9.09018V4.93018H17.0003C20.8403 4.93018 22.0003 6.09018 22.0003 9.93018V10.7802C22.0003 11.1702 21.6803 11.4802 21.3003 11.4802C20.5003 11.4802 19.8503 12.1302 19.8503 12.9402Z" fill="#19cca8"></path> <path d="M11.0701 9.08981C10.6601 9.08981 10.2501 9.41981 10.2501 9.82981V14.1698C10.2501 14.5798 10.5901 14.9198 11.0001 14.9198C11.0201 14.9198 11.0501 14.9198 11.0701 14.9098V17.7598C11.0501 17.7498 11.0201 17.7498 11.0001 17.7498C10.5901 17.7498 10.2501 18.0898 10.2501 18.4998V20.0898H8.49009C6.61009 20.0898 5.64009 18.6798 4.76009 16.5498L4.59009 16.1298C4.45009 15.7698 4.62009 15.3598 4.98009 15.2198C5.35009 15.0798 5.64009 14.7898 5.79009 14.4098C5.95009 14.0398 5.95009 13.6298 5.80009 13.2598C5.48009 12.4898 4.60009 12.1198 3.82009 12.4298C3.65009 12.5098 3.45009 12.5098 3.28009 12.4298C3.11009 12.3598 2.98009 12.2198 2.90009 12.0398L2.75009 11.6398C1.26009 8.01981 1.91009 6.46981 5.53009 4.96981L7.98009 3.95981C8.34009 3.80981 8.75009 3.97981 8.89009 4.33981L11.0701 9.08981Z" fill="#19cca8"></path> </g></svg>
                        <span class="text-3xl font-bold text-teal-600 dark:text-teal-500">Eventure</span>
                    </a>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('home')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Home
                        </a>
                        <a href="{{ route('features') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('features')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Features
                        </a>
                        <a href="{{ route('how-it-works') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('how-it-works')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            How It Works
                        </a>
                        <a href="{{ route('pricing') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('pricing')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Pricing
                        </a>
                        <a href="{{ route('contact') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('contact')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Contact
                        </a>
                        <a href="{{ route('user.events') }}"
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('user.events') || request()->routeIs('user.event.*')
                                ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                            Events
                        </a>
                        {{-- <a href="{{ route('support') }}"
                                    class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('user-dashboard')
                                        ? 'text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-900/20 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-600 before:rounded-b-md'
                                        : 'text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}">
                                    Support
                                </a> --}}
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
                        <a href="{{ route('features') }}"
                            class="block py-2 px-3 rounded-md {{ request()->routeIs('features') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                            Features
                        </a>
                        <a href="{{ route('how-it-works') }}"
                            class="block py-2 px-3 rounded-md {{ request()->routeIs('how-it-works') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                            How It Works
                        </a>
                        <a href="{{ route('pricing') }}"
                            class="block py-2 px-3 rounded-md {{ request()->routeIs('pricing') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                            Pricing
                        </a>
                        <a href="{{ route('contact') }}"
                            class="block py-2 px-3 rounded-md {{ request()->routeIs('contact') ? 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/30 hover:text-teal-600 dark:hover:text-teal-400' }}">
                            Contact
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

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white py-32 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <img src="{{ asset('images/background.avif') }}" alt="Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-[url('https://raw.githubusercontent.com/pattern-library/pattern-library/main/patterns/circuit-board.svg')] opacity-60"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-gray-00/95 opacity-90"></div>
            </div>
            <div class="container mx-auto px-4 relative">
                <div class="max-w-3xl">
                    <h1 class="text-5xl font-bold mb-6">Discover Amazing Events Near You</h1>
                    <p class="text-xl text-gray-300 mb-8">From concerts to workshops, find and book your next unforgettable experience.</p>
                    <a href="{{ route('user.events') }}" class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-8 py-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 text-lg font-medium inline-block">
                        Explore Events
                    </a>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="py-20 bg-gray-50 dark:bg-gray-900 relative overflow-hidden">
            <!-- Background Circles -->
            <div class="absolute inset-0 pointer-events-none">
            <div class="absolute w-32 h-32 bg-teal-500 rounded-full opacity-50 top-7 left-10"></div>
            <div class="absolute w-48 h-48 bg-pink-500 rounded-full opacity-30 top-28 right-20"></div>
            <div class="absolute w-24 h-24 bg-yellow-500 rounded-full opacity-30 bottom-7 left-20 "></div>
            <div class="absolute w-40 h-40 bg-blue-500 rounded-full opacity-30 bottom-6 right-10"></div>
            <div class="absolute w-28 h-28 bg-purple-500 rounded-full opacity-30 top-1/2 left-1/3 transform -translate-y-1/2"></div>
            </div>

            <div class="container mx-auto px-4 relative">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">Browse by Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Music Category -->
                <a href="{{ route('user.events', ['selectedCategory' => 'Music']) }}" class="group">
                <div class="relative h-64 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 group-hover:scale-105">
                    <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=1974" alt="Music Events" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-2xl font-bold text-white mb-1">Music</h3>
                    <p class="text-gray-200">Concerts & Festivals</p>
                    </div>
                </div>
                </a>

                <!-- Sports Category -->
                <a href="{{ route('user.events', ['selectedCategory' => 'Sports']) }}" class="group">
                <div class="relative h-64 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 group-hover:scale-105">
                    <img src="https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?q=80&w=2070" alt="Sports Events" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-2xl font-bold text-white mb-1">Sports</h3>
                    <p class="text-gray-200">Games & Tournaments</p>
                    </div>
                </div>
                </a>

                <!-- Business Category -->
                <a href="{{ route('user.events', ['selectedCategory' => 'Business']) }}" class="group">
                <div class="relative h-64 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 group-hover:scale-105">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?q=80&w=1974" alt="Business Events" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-2xl font-bold text-white mb-1">Business</h3>
                    <p class="text-gray-200">Conferences & Networking</p>
                    </div>
                </div>
                </a>

                <!-- Education Category -->
                <a href="{{ route('user.events', ['selectedCategory' => 'Education']) }}" class="group">
                <div class="relative h-64 rounded-lg overflow-hidden shadow-lg transition-transform duration-300 group-hover:scale-105">
                    <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=2070" alt="Education Events" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-2xl font-bold text-white mb-1">Education</h3>
                    <p class="text-gray-200">Workshops & Seminars</p>
                    </div>
                </div>
                </a>
            </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-white dark:bg-gray-800 relative overflow-hidden">
            <div class="absolute inset-0 opacity-40">
            <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?q=80&w=2070" alt="Background" class="w-full h-full object-cover">
            </div>
            <div class="container mx-auto px-4 relative">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-16">Why Choose Eventure?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Easy Booking -->
                <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-2xl flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <div class="w-16 h-16 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center shadow-lg shadow-teal-500/50 transform transition-transform duration-500 group-hover:rotate-15 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Easy Booking</h3>
                <p class="text-gray-600 dark:text-gray-400">Simple and secure booking process with instant confirmation.</p>
                </div>

                <!-- Diverse Events -->
                <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-2xl flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <div class="w-16 h-16 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center shadow-lg shadow-teal-500/50 transform transition-transform duration-500 group-hover:rotate-15 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Diverse Events</h3>
                <p class="text-gray-600 dark:text-gray-400">From local meetups to international conferences, find your perfect event.</p>
                </div>

                <!-- Customer Support -->
                <div class="text-center group">
                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-2xl flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <div class="w-16 h-16 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center shadow-lg shadow-teal-500/50 transform transition-transform duration-500 group-hover:rotate-15 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">24/7 Support</h3>
                <p class="text-gray-600 dark:text-gray-400">Dedicated support team ready to help you anytime, anywhere.</p>
                </div>
            </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-20 bg-gray-50 dark:bg-gray-900 relative overflow-hidden">
            <div class="absolute inset-0 opacity-30 dark:opacity-10">
                <img src="https://raw.githubusercontent.com/pattern-library/pattern-library/main/patterns/topography.svg" alt="" class="w-full h-full object-cover">
            </div>
            <div class="container mx-auto px-4 relative">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">What Our Users Say</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105">
                        <div class="flex items-center mb-6">
                            <img src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?q=80&w=1770" alt="Sarah Johnson" class="w-16 h-16 rounded-full object-cover ring-4 ring-teal-50 dark:ring-teal-900">
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Sarah Johnson</h4>
                                <p class="text-teal-600 dark:text-teal-400">Event Organizer</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 italic">"Eventure has transformed how I manage my events. The platform is intuitive and the support team is exceptional."</p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105">
                        <div class="flex items-center mb-6">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=1770" alt="Michael Chen" class="w-16 h-16 rounded-full object-cover ring-4 ring-teal-50 dark:ring-teal-900">
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Michael Chen</h4>
                                <p class="text-teal-600 dark:text-teal-400">Regular Attendee</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 italic">"I've discovered amazing events through Eventure. The booking process is seamless and the event suggestions are spot-on."</p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105">
                        <div class="flex items-center mb-6">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1974" alt="Emily Rodriguez" class="w-16 h-16 rounded-full object-cover ring-4 ring-teal-50 dark:ring-teal-900">
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white text-lg">Emily Rodriguez</h4>
                                <p class="text-teal-600 dark:text-teal-400">Corporate Event Manager</p>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 italic">"Using Eventure for our corporate events has been a game-changer. The platform is reliable and professional."</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-teal-600 to-teal-00 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-50">
                <img src="https://images.unsplash.com/photo-1519677100203-a0e668c92439?q=80&w=2070" alt="Party Background" class="w-full h-full object-cover">
            </div>
            <div class="container mx-auto px-4 text-center relative">
                <h2 class="text-4xl font-bold mb-6">Ready to Experience Amazing Events?</h2>
                <p class="text-xl text-teal-100 mb-12 max-w-2xl mx-auto">Join thousands of people discovering incredible events every day.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 max-w-md mx-auto">
                    <a href="{{ route('user.events') }}" class="bg-white text-teal-600 px-8 py-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 font-medium flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Browse Events
                    </a>
                    <a href="{{ route('register') }}" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-teal-600 transition-all duration-300 font-medium flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Sign Up Now
                    </a>
                </div>
            </div>
        </section>

        <!-- Advertisers Section -->
        <section class="py-20 bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-12">Our Advertisers</h2>
                <div class="text-center mb-8">
                    <p class="text-lg font-semibold text-teal-600 dark:text-teal-400">
                        "Partnering with the best to bring you unforgettable experiences."
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Explore events powered by our trusted advertisers.
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <!-- Advertiser 1 -->
                    <div class="flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                        <svg width="106px" height="106px" viewBox="-4 0 264 264" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" fill="#000000" stroke="#000000" stroke-width="0.00264"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M255.855641,59.619717 C255.950565,59.9710596 256,60.3333149 256,60.6972536 L256,117.265345 C256,118.743206 255.209409,120.108149 253.927418,120.843385 L206.448786,148.178786 L206.448786,202.359798 C206.448786,203.834322 205.665123,205.195421 204.386515,205.937838 L105.27893,262.990563 C105.05208,263.119455 104.804608,263.201946 104.557135,263.289593 C104.464333,263.320527 104.376687,263.377239 104.278729,263.403017 C103.585929,263.58546 102.857701,263.58546 102.164901,263.403017 C102.051476,263.372083 101.948363,263.310215 101.840093,263.26897 C101.613244,263.186479 101.376082,263.1143 101.159544,262.990563 L2.07258227,205.937838 C0.7913718,205.201819 0,203.837372 0,202.359798 L0,32.6555248 C0,32.2843161 0.0515567729,31.9234187 0.144358964,31.5728326 C0.175293028,31.454252 0.24747251,31.3459828 0.288717928,31.2274022 C0.366053087,31.0108638 0.438232569,30.7891697 0.55165747,30.5880982 C0.628992629,30.4540506 0.742417529,30.3457814 0.83521972,30.2220451 C0.953800298,30.0570635 1.06206952,29.8869261 1.20127281,29.7425672 C1.31985339,29.6239866 1.4745237,29.5363401 1.60857131,29.4332265 C1.75808595,29.3094903 1.89213356,29.1754427 2.06227091,29.0774848 L2.06742659,29.0774848 L51.6134853,0.551122364 C52.8901903,-0.183535768 54.4613221,-0.183535768 55.7380271,0.551122364 L105.284086,29.0774848 L105.294397,29.0774848 C105.459379,29.1805983 105.598582,29.3094903 105.748097,29.4280708 C105.882144,29.5311844 106.031659,29.6239866 106.15024,29.7374115 C106.294599,29.8869261 106.397712,30.0570635 106.521448,30.2220451 C106.609095,30.3457814 106.727676,30.4540506 106.799855,30.5880982 C106.918436,30.7943253 106.985459,31.0108638 107.06795,31.2274022 C107.109196,31.3459828 107.181375,31.454252 107.212309,31.5779883 C107.307234,31.9293308 107.355765,32.2915861 107.356668,32.6555248 L107.356668,138.651094 L148.643332,114.878266 L148.643332,60.6920979 C148.643332,60.3312005 148.694889,59.9651474 148.787691,59.619717 C148.823781,59.4959808 148.890804,59.3877116 148.93205,59.269131 C149.014541,59.0525925 149.08672,58.8308984 149.200145,58.629827 C149.27748,58.4957794 149.390905,58.3875102 149.478552,58.2637739 C149.602288,58.0987922 149.705401,57.9286549 149.84976,57.7842959 C149.968341,57.6657153 150.117856,57.5780688 150.251903,57.4749553 C150.406573,57.351219 150.540621,57.2171714 150.705603,57.1192136 L150.710758,57.1192136 L200.261973,28.5928511 C201.538395,27.8571345 203.110093,27.8571345 204.386515,28.5928511 L253.932573,57.1192136 C254.107866,57.2223271 254.241914,57.351219 254.396584,57.4697996 C254.525476,57.5729132 254.674991,57.6657153 254.793572,57.7791402 C254.93793,57.9286549 255.041044,58.0987922 255.16478,58.2637739 C255.257582,58.3875102 255.371007,58.4957794 255.443187,58.629827 C255.561767,58.8308984 255.628791,59.0525925 255.711282,59.269131 C255.757683,59.3877116 255.824707,59.4959808 255.855641,59.619717 Z M247.740605,114.878266 L247.740605,67.8378666 L230.402062,77.8192579 L206.448786,91.6106946 L206.448786,138.651094 L247.745761,114.878266 L247.740605,114.878266 Z M198.194546,199.97272 L198.194546,152.901386 L174.633101,166.357704 L107.351512,204.757188 L107.351512,252.27191 L198.194546,199.97272 Z M8.25939501,39.7961379 L8.25939501,199.97272 L99.0921175,252.266755 L99.0921175,204.762344 L51.6392637,177.906421 L51.6237967,177.89611 L51.603174,177.885798 C51.443348,177.792996 51.3093004,177.658949 51.1597857,177.545524 C51.0308938,177.44241 50.8813791,177.359919 50.7679542,177.246494 L50.7576429,177.231027 C50.6235953,177.102135 50.5307931,176.942309 50.4173682,176.79795 C50.3142546,176.658747 50.1905184,176.540167 50.1080276,176.395808 L50.1028719,176.380341 C50.0100697,176.22567 49.9533572,176.040066 49.8863334,175.864773 C49.8193096,175.710103 49.7316631,175.565744 49.6904177,175.400762 L49.6904177,175.395606 C49.6388609,175.19969 49.6285496,174.993463 49.6079269,174.792392 C49.5873041,174.637722 49.5460587,174.483051 49.5460587,174.328381 L49.5460587,174.31807 L49.5460587,63.5689658 L25.5979377,49.7723734 L8.25939501,39.8012935 L8.25939501,39.7961379 Z M53.6809119,8.89300821 L12.3994039,32.6555248 L53.6706006,56.4180414 L94.9469529,32.6503692 L53.6706006,8.89300821 L53.6809119,8.89300821 Z M75.1491521,157.19091 L99.0972731,143.404629 L99.0972731,39.7961379 L81.7587304,49.7775291 L57.8054537,63.5689658 L57.8054537,167.177457 L75.1491521,157.19091 Z M202.324244,36.934737 L161.047891,60.6972536 L202.324244,84.4597702 L243.59544,60.6920979 L202.324244,36.934737 Z M198.194546,91.6106946 L174.24127,77.8192579 L156.902727,67.8378666 L156.902727,114.878266 L180.850848,128.664547 L198.194546,138.651094 L198.194546,91.6106946 Z M103.216659,197.616575 L163.759778,163.052915 L194.023603,145.781396 L152.778185,122.034346 L105.289242,149.374903 L62.0073307,174.292291 L103.216659,197.616575 Z" fill="#FF2D20"> </path> </g> </g></svg>
                    </div>
                    <!-- Advertiser 2 -->
                    <div class="flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                        <svg width="106px" height="106px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>file_type_vscode-insiders</title><path d="M20.375,3.291a.874.874,0,0,1,1.463.647V10.25l-8.36,6.624L9.172,13.608Z" style="fill:#009a7c"></path><path d="M6.013,16.669,2.38,19.8A1.166,1.166,0,0,0,2.3,21.447c.025.027.05.053.077.077l1.541,1.4a1.166,1.166,0,0,0,1.489.066L9.6,19.935Z" style="fill:#009a7c"></path><path d="M21.838,21.749,5.412,9.007a1.165,1.165,0,0,0-1.489.066l-1.541,1.4a1.166,1.166,0,0,0-.077,1.647c.025.027.05.053.077.077l17.99,16.5a.875.875,0,0,0,1.466-.645Z" style="fill:#00b294"></path><path d="M23.244,29.747a1.745,1.745,0,0,1-1.989-.338A1.025,1.025,0,0,0,23,28.684V3.316a1.025,1.025,0,0,0-1.749-.725,1.745,1.745,0,0,1,1.989-.338l5.765,2.772A1.748,1.748,0,0,1,30,6.6V25.4a1.748,1.748,0,0,1-.991,1.576Z" style="fill:#24bfa5"></path></g></svg>
                    </div>
                    <!-- Advertiser 3 -->
                    <div class="flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                          <svg width="106px" height="106px" viewBox="0 0 73 73" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>team-collaboration/version-control/github</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="team-collaboration/version-control/github" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="container" transform="translate(2.000000, 2.000000)" fill-rule="nonzero"> <rect id="mask" stroke="#000000" stroke-width="2" fill="#000000" x="-1" y="-1" width="71" height="71" rx="14"> </rect> <path d="M58.3067362,21.4281798 C55.895743,17.2972267 52.6253846,14.0267453 48.4948004,11.615998 C44.3636013,9.20512774 39.8535636,8 34.9614901,8 C30.0700314,8 25.5585181,9.20549662 21.4281798,11.615998 C17.2972267,14.0266224 14.0269912,17.2972267 11.615998,21.4281798 C9.20537366,25.5590099 8,30.0699084 8,34.9607523 C8,40.8357654 9.71405782,46.1187277 13.1430342,50.8109917 C16.5716416,55.5036246 21.0008949,58.7507436 26.4304251,60.5527176 C27.0624378,60.6700211 27.5302994,60.5875152 27.8345016,60.3072901 C28.1388268,60.0266961 28.290805,59.6752774 28.290805,59.2545094 C28.290805,59.1842994 28.2847799,58.5526556 28.2730988,57.3588401 C28.2610487,56.1650247 28.2553926,55.1235563 28.2553926,54.2349267 L27.4479164,54.3746089 C26.9330843,54.468919 26.2836113,54.5088809 25.4994975,54.4975686 C24.7157525,54.4866252 23.9021284,54.4044881 23.0597317,54.2517722 C22.2169661,54.1004088 21.4330982,53.749359 20.7075131,53.1993604 C19.982297,52.6493618 19.4674649,51.9294329 19.1631397,51.0406804 L18.8120898,50.2328353 C18.5780976,49.6950097 18.2097104,49.0975487 17.7064365,48.4426655 C17.2031625,47.7871675 16.6942324,47.3427912 16.1794003,47.108799 L15.9336039,46.9328437 C15.7698216,46.815909 15.6178435,46.6748743 15.4773006,46.511215 C15.3368806,46.3475556 15.2317501,46.1837734 15.1615401,46.0197452 C15.0912072,45.855594 15.1494901,45.7209532 15.3370036,45.6153308 C15.5245171,45.5097084 15.8633939,45.4584343 16.3551097,45.4584343 L17.0569635,45.5633189 C17.5250709,45.6571371 18.104088,45.9373622 18.7947525,46.4057156 C19.4850481,46.8737001 20.052507,47.4821045 20.4972521,48.230683 C21.0358155,49.1905062 21.6846737,49.9218703 22.4456711,50.4251443 C23.2060537,50.9284182 23.9727072,51.1796248 24.744894,51.1796248 C25.5170807,51.1796248 26.1840139,51.121096 26.7459396,51.0046532 C27.3072505,50.8875956 27.8338868,50.7116403 28.3256025,50.477771 C28.5362325,48.9090515 29.1097164,47.7039238 30.0455624,46.8615271 C28.7116959,46.721353 27.5124702,46.5102313 26.4472706,46.2295144 C25.3826858,45.9484285 24.2825656,45.4922482 23.1476478,44.8597436 C22.0121153,44.2280998 21.0701212,43.44374 20.3214198,42.5080169 C19.5725954,41.571802 18.9580429,40.3426971 18.4786232,38.821809 C17.9989575,37.300306 17.7590632,35.5451796 17.7590632,33.5559381 C17.7590632,30.7235621 18.6837199,28.3133066 20.5326645,26.3238191 C19.6665366,24.1944035 19.7483048,21.8072644 20.778215,19.1626478 C21.4569523,18.951772 22.4635002,19.1100211 23.7973667,19.6364115 C25.1314792,20.1630477 26.1082708,20.6141868 26.7287253,20.9882301 C27.3491798,21.3621504 27.8463057,21.6790175 28.2208409,21.9360032 C30.3978419,21.3277217 32.644438,21.0235195 34.9612442,21.0235195 C37.2780503,21.0235195 39.5251383,21.3277217 41.7022622,21.9360032 L43.0362517,21.0938524 C43.9484895,20.5319267 45.0257392,20.0169716 46.2654186,19.5488642 C47.5058357,19.0810026 48.4543466,18.9521409 49.1099676,19.1630167 C50.1627483,21.8077563 50.2565666,24.1947724 49.3901927,26.324188 C51.2390143,28.3136755 52.1640399,30.7245457 52.1640399,33.556307 C52.1640399,35.5455485 51.9232849,37.3062081 51.444357,38.8393922 C50.9648143,40.3728223 50.3449746,41.6006975 49.5845919,42.5256002 C48.8233486,43.4503799 47.8753296,44.2285916 46.7404118,44.8601125 C45.6052481,45.4921252 44.504759,45.9483056 43.4401742,46.2293914 C42.3750975,46.5104772 41.1758719,46.7217219 39.8420054,46.8621419 C41.0585683,47.9149226 41.6669728,49.5767225 41.6669728,51.846804 L41.6669728,59.2535257 C41.6669728,59.6742937 41.8132948,60.0255895 42.1061847,60.3063064 C42.3987058,60.5865315 42.8606653,60.6690374 43.492678,60.5516109 C48.922946,58.7498829 53.3521992,55.5026409 56.7806837,50.810008 C60.2087994,46.117744 61.923472,40.8347817 61.923472,34.9597686 C61.9222424,30.0695396 60.7162539,25.5590099 58.3067362,21.4281798 Z" id="Shape" fill="#FFFFFF"> </path> </g> </g> </g></svg>
                        {{-- <svg width="106px" height="106px" viewBox="-4 0 264 264" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" fill="#000000" stroke="#000000" stroke-width="0.00264"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M255.855641,59.619717 C255.950565,59.9710596 256,60.3333149 256,60.6972536 L256,117.265345 C256,118.743206 255.209409,120.108149 253.927418,120.843385 L206.448786,148.178786 L206.448786,202.359798 C206.448786,203.834322 205.665123,205.195421 204.386515,205.937838 L105.27893,262.990563 C105.05208,263.119455 104.804608,263.201946 104.557135,263.289593 C104.464333,263.320527 104.376687,263.377239 104.278729,263.403017 C103.585929,263.58546 102.857701,263.58546 102.164901,263.403017 C102.051476,263.372083 101.948363,263.310215 101.840093,263.26897 C101.613244,263.186479 101.376082,263.1143 101.159544,262.990563 L2.07258227,205.937838 C0.7913718,205.201819 0,203.837372 0,202.359798 L0,32.6555248 C0,32.2843161 0.0515567729,31.9234187 0.144358964,31.5728326 C0.175293028,31.454252 0.24747251,31.3459828 0.288717928,31.2274022 C0.366053087,31.0108638 0.438232569,30.7891697 0.55165747,30.5880982 C0.628992629,30.4540506 0.742417529,30.3457814 0.83521972,30.2220451 C0.953800298,30.0570635 1.06206952,29.8869261 1.20127281,29.7425672 C1.31985339,29.6239866 1.4745237,29.5363401 1.60857131,29.4332265 C1.75808595,29.3094903 1.89213356,29.1754427 2.06227091,29.0774848 L2.06742659,29.0774848 L51.6134853,0.551122364 C52.8901903,-0.183535768 54.4613221,-0.183535768 55.7380271,0.551122364 L105.284086,29.0774848 L105.294397,29.0774848 C105.459379,29.1805983 105.598582,29.3094903 105.748097,29.4280708 C105.882144,29.5311844 106.031659,29.6239866 106.15024,29.7374115 C106.294599,29.8869261 106.397712,30.0570635 106.521448,30.2220451 C106.609095,30.3457814 106.727676,30.4540506 106.799855,30.5880982 C106.918436,30.7943253 106.985459,31.0108638 107.06795,31.2274022 C107.109196,31.3459828 107.181375,31.454252 107.212309,31.5779883 C107.307234,31.9293308 107.355765,32.2915861 107.356668,32.6555248 L107.356668,138.651094 L148.643332,114.878266 L148.643332,60.6920979 C148.643332,60.3312005 148.694889,59.9651474 148.787691,59.619717 C148.823781,59.4959808 148.890804,59.3877116 148.93205,59.269131 C149.014541,59.0525925 149.08672,58.8308984 149.200145,58.629827 C149.27748,58.4957794 149.390905,58.3875102 149.478552,58.2637739 C149.602288,58.0987922 149.705401,57.9286549 149.84976,57.7842959 C149.968341,57.6657153 150.117856,57.5780688 150.251903,57.4749553 C150.406573,57.351219 150.540621,57.2171714 150.705603,57.1192136 L150.710758,57.1192136 L200.261973,28.5928511 C201.538395,27.8571345 203.110093,27.8571345 204.386515,28.5928511 L253.932573,57.1192136 C254.107866,57.2223271 254.241914,57.351219 254.396584,57.4697996 C254.525476,57.5729132 254.674991,57.6657153 254.793572,57.7791402 C254.93793,57.9286549 255.041044,58.0987922 255.16478,58.2637739 C255.257582,58.3875102 255.371007,58.4957794 255.443187,58.629827 C255.561767,58.8308984 255.628791,59.0525925 255.711282,59.269131 C255.757683,59.3877116 255.824707,59.4959808 255.855641,59.619717 Z M247.740605,114.878266 L247.740605,67.8378666 L230.402062,77.8192579 L206.448786,91.6106946 L206.448786,138.651094 L247.745761,114.878266 L247.740605,114.878266 Z M198.194546,199.97272 L198.194546,152.901386 L174.633101,166.357704 L107.351512,204.757188 L107.351512,252.27191 L198.194546,199.97272 Z M8.25939501,39.7961379 L8.25939501,199.97272 L99.0921175,252.266755 L99.0921175,204.762344 L51.6392637,177.906421 L51.6237967,177.89611 L51.603174,177.885798 C51.443348,177.792996 51.3093004,177.658949 51.1597857,177.545524 C51.0308938,177.44241 50.8813791,177.359919 50.7679542,177.246494 L50.7576429,177.231027 C50.6235953,177.102135 50.5307931,176.942309 50.4173682,176.79795 C50.3142546,176.658747 50.1905184,176.540167 50.1080276,176.395808 L50.1028719,176.380341 C50.0100697,176.22567 49.9533572,176.040066 49.8863334,175.864773 C49.8193096,175.710103 49.7316631,175.565744 49.6904177,175.400762 L49.6904177,175.395606 C49.6388609,175.19969 49.6285496,174.993463 49.6079269,174.792392 C49.5873041,174.637722 49.5460587,174.483051 49.5460587,174.328381 L49.5460587,174.31807 L49.5460587,63.5689658 L25.5979377,49.7723734 L8.25939501,39.8012935 L8.25939501,39.7961379 Z M53.6809119,8.89300821 L12.3994039,32.6555248 L53.6706006,56.4180414 L94.9469529,32.6503692 L53.6706006,8.89300821 L53.6809119,8.89300821 Z M75.1491521,157.19091 L99.0972731,143.404629 L99.0972731,39.7961379 L81.7587304,49.7775291 L57.8054537,63.5689658 L57.8054537,167.177457 L75.1491521,157.19091 Z M202.324244,36.934737 L161.047891,60.6972536 L202.324244,84.4597702 L243.59544,60.6920979 L202.324244,36.934737 Z M198.194546,91.6106946 L174.24127,77.8192579 L156.902727,67.8378666 L156.902727,114.878266 L180.850848,128.664547 L198.194546,138.651094 L198.194546,91.6106946 Z M103.216659,197.616575 L163.759778,163.052915 L194.023603,145.781396 L152.778185,122.034346 L105.289242,149.374903 L62.0073307,174.292291 L103.216659,197.616575 Z" fill="#FF2D20"> </path> </g> </g></svg> --}}
                    </div>
                    <!-- Advertiser 4 -->
                    <div class="flex items-center justify-center transform transition-transform duration-300 hover:scale-110">
                        <svg width="106px" height="106px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="512" cy="512" r="512" style="fill:#635bff"></circle> <path d="M781.67 515.75c0-38.35-18.58-68.62-54.08-68.62s-57.23 30.26-57.23 68.32c0 45.09 25.47 67.87 62 67.87 17.83 0 31.31-4 41.5-9.74v-30c-10.19 5.09-21.87 8.24-36.7 8.24-14.53 0-27.42-5.09-29.06-22.77h73.26c.01-1.92.31-9.71.31-13.3zm-74-14.23c0-16.93 10.34-24 19.78-24 9.14 0 18.88 7 18.88 24zm-95.14-54.39a42.32 42.32 0 0 0-29.36 11.69l-1.95-9.29h-33v174.68l37.45-7.94.15-42.4c5.39 3.9 13.33 9.44 26.52 9.44 26.82 0 51.24-21.57 51.24-69.06-.12-43.45-24.84-67.12-51.05-67.12zm-9 103.22c-8.84 0-14.08-3.15-17.68-7l-.15-55.58c3.9-4.34 9.29-7.34 17.83-7.34 13.63 0 23.07 15.28 23.07 34.91.01 20.03-9.28 35.01-23.06 35.01zM496.72 438.29l37.6-8.09v-30.41l-37.6 7.94v30.56zm0 11.39h37.6v131.09h-37.6zm-40.3 11.08L454 449.68h-32.34v131.08h37.45v-88.84c8.84-11.54 23.82-9.44 28.46-7.79v-34.45c-4.78-1.8-22.31-5.1-31.15 11.08zm-74.91-43.59L345 425l-.15 120c0 22.17 16.63 38.5 38.8 38.5 12.28 0 21.27-2.25 26.22-4.94v-30.45c-4.79 1.95-28.46 8.84-28.46-13.33v-53.19h28.46v-31.91h-28.51zm-101.27 70.56c0-5.84 4.79-8.09 12.73-8.09a83.56 83.56 0 0 1 37.15 9.59V454a98.8 98.8 0 0 0-37.12-6.87c-30.41 0-50.64 15.88-50.64 42.4 0 41.35 56.93 34.76 56.93 52.58 0 6.89-6 9.14-14.38 9.14-12.43 0-28.32-5.09-40.9-12v35.66a103.85 103.85 0 0 0 40.9 8.54c31.16 0 52.58-15.43 52.58-42.25-.17-44.63-57.25-36.69-57.25-53.47z" style="fill:#fff"></path> </g></svg>
                        {{-- <svg width="106px" height="106px" viewBox="-4 0 264 264" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" fill="#000000" stroke="#000000" stroke-width="0.00264"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M255.855641,59.619717 C255.950565,59.9710596 256,60.3333149 256,60.6972536 L256,117.265345 C256,118.743206 255.209409,120.108149 253.927418,120.843385 L206.448786,148.178786 L206.448786,202.359798 C206.448786,203.834322 205.665123,205.195421 204.386515,205.937838 L105.27893,262.990563 C105.05208,263.119455 104.804608,263.201946 104.557135,263.289593 C104.464333,263.320527 104.376687,263.377239 104.278729,263.403017 C103.585929,263.58546 102.857701,263.58546 102.164901,263.403017 C102.051476,263.372083 101.948363,263.310215 101.840093,263.26897 C101.613244,263.186479 101.376082,263.1143 101.159544,262.990563 L2.07258227,205.937838 C0.7913718,205.201819 0,203.837372 0,202.359798 L0,32.6555248 C0,32.2843161 0.0515567729,31.9234187 0.144358964,31.5728326 C0.175293028,31.454252 0.24747251,31.3459828 0.288717928,31.2274022 C0.366053087,31.0108638 0.438232569,30.7891697 0.55165747,30.5880982 C0.628992629,30.4540506 0.742417529,30.3457814 0.83521972,30.2220451 C0.953800298,30.0570635 1.06206952,29.8869261 1.20127281,29.7425672 C1.31985339,29.6239866 1.4745237,29.5363401 1.60857131,29.4332265 C1.75808595,29.3094903 1.89213356,29.1754427 2.06227091,29.0774848 L2.06742659,29.0774848 L51.6134853,0.551122364 C52.8901903,-0.183535768 54.4613221,-0.183535768 55.7380271,0.551122364 L105.284086,29.0774848 L105.294397,29.0774848 C105.459379,29.1805983 105.598582,29.3094903 105.748097,29.4280708 C105.882144,29.5311844 106.031659,29.6239866 106.15024,29.7374115 C106.294599,29.8869261 106.397712,30.0570635 106.521448,30.2220451 C106.609095,30.3457814 106.727676,30.4540506 106.799855,30.5880982 C106.918436,30.7943253 106.985459,31.0108638 107.06795,31.2274022 C107.109196,31.3459828 107.181375,31.454252 107.212309,31.5779883 C107.307234,31.9293308 107.355765,32.2915861 107.356668,32.6555248 L107.356668,138.651094 L148.643332,114.878266 L148.643332,60.6920979 C148.643332,60.3312005 148.694889,59.9651474 148.787691,59.619717 C148.823781,59.4959808 148.890804,59.3877116 148.93205,59.269131 C149.014541,59.0525925 149.08672,58.8308984 149.200145,58.629827 C149.27748,58.4957794 149.390905,58.3875102 149.478552,58.2637739 C149.602288,58.0987922 149.705401,57.9286549 149.84976,57.7842959 C149.968341,57.6657153 150.117856,57.5780688 150.251903,57.4749553 C150.406573,57.351219 150.540621,57.2171714 150.705603,57.1192136 L150.710758,57.1192136 L200.261973,28.5928511 C201.538395,27.8571345 203.110093,27.8571345 204.386515,28.5928511 L253.932573,57.1192136 C254.107866,57.2223271 254.241914,57.351219 254.396584,57.4697996 C254.525476,57.5729132 254.674991,57.6657153 254.793572,57.7791402 C254.93793,57.9286549 255.041044,58.0987922 255.16478,58.2637739 C255.257582,58.3875102 255.371007,58.4957794 255.443187,58.629827 C255.561767,58.8308984 255.628791,59.0525925 255.711282,59.269131 C255.757683,59.3877116 255.824707,59.4959808 255.855641,59.619717 Z M247.740605,114.878266 L247.740605,67.8378666 L230.402062,77.8192579 L206.448786,91.6106946 L206.448786,138.651094 L247.745761,114.878266 L247.740605,114.878266 Z M198.194546,199.97272 L198.194546,152.901386 L174.633101,166.357704 L107.351512,204.757188 L107.351512,252.27191 L198.194546,199.97272 Z M8.25939501,39.7961379 L8.25939501,199.97272 L99.0921175,252.266755 L99.0921175,204.762344 L51.6392637,177.906421 L51.6237967,177.89611 L51.603174,177.885798 C51.443348,177.792996 51.3093004,177.658949 51.1597857,177.545524 C51.0308938,177.44241 50.8813791,177.359919 50.7679542,177.246494 L50.7576429,177.231027 C50.6235953,177.102135 50.5307931,176.942309 50.4173682,176.79795 C50.3142546,176.658747 50.1905184,176.540167 50.1080276,176.395808 L50.1028719,176.380341 C50.0100697,176.22567 49.9533572,176.040066 49.8863334,175.864773 C49.8193096,175.710103 49.7316631,175.565744 49.6904177,175.400762 L49.6904177,175.395606 C49.6388609,175.19969 49.6285496,174.993463 49.6079269,174.792392 C49.5873041,174.637722 49.5460587,174.483051 49.5460587,174.328381 L49.5460587,174.31807 L49.5460587,63.5689658 L25.5979377,49.7723734 L8.25939501,39.8012935 L8.25939501,39.7961379 Z M53.6809119,8.89300821 L12.3994039,32.6555248 L53.6706006,56.4180414 L94.9469529,32.6503692 L53.6706006,8.89300821 L53.6809119,8.89300821 Z M75.1491521,157.19091 L99.0972731,143.404629 L99.0972731,39.7961379 L81.7587304,49.7775291 L57.8054537,63.5689658 L57.8054537,167.177457 L75.1491521,157.19091 Z M202.324244,36.934737 L161.047891,60.6972536 L202.324244,84.4597702 L243.59544,60.6920979 L202.324244,36.934737 Z M198.194546,91.6106946 L174.24127,77.8192579 L156.902727,67.8378666 L156.902727,114.878266 L180.850848,128.664547 L198.194546,138.651094 L198.194546,91.6106946 Z M103.216659,197.616575 L163.759778,163.052915 L194.023603,145.781396 L152.778185,122.034346 L105.289242,149.374903 L62.0073307,174.292291 L103.216659,197.616575 Z" fill="#FF2D20"> </path> </g> </g></svg> --}}
                    </div>
                </div>
            </div>
        </section>

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
                            <li><a href="{{ route('features') }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Features</a>
                            </li>
                            <li><a href="{{ route('how-it-works') }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">How
                                    It Works</a></li>
                            <li><a href="{{ route('pricing') }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-teal-600 dark:hover:text-teal-400">Pricing</a>
                            </li>
                            <li><a href="{{ route('contact') }}"
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
