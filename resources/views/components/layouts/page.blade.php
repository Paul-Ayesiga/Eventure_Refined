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
                    {{-- <a href="{{ route('home') }}" class="flex items-center">
                        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 7h4a2 2 0 012 2v6a2 2 0 01-2 2h-4m-6 0H5a2 2 0 01-2-2V9a2 2 0 012-2h4m6 0v10m-6 0V7'/></svg>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 dark:text-teal-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7h4a2 2 0 012 2v6a2 2 0 01-2 2h-4m-6 0H5a2 2 0 01-2-2V9a2 2 0 012-2h4m6 0v10m-6 0V7" />
                        </svg>
                        <span class="text-2xl font-bold text-teal-600 dark:text-teal-400">Eventure</span>
                    </a> --}}

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
                            class="relative px-3 py-2 rounded-md transition-all duration-200 {{ request()->routeIs('user.events') || request()->routeIs('user.event.*')
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
        {{-- <section class="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white py-32 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070" alt="Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-[url('https://raw.githubusercontent.com/pattern-library/pattern-library/main/patterns/circuit-board.svg')] opacity-60"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900 to-gray-800/95 opacity-90"></div>
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
        </section> --}}

        <!-- Featured Categories -->
        {{-- <section class="py-20 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4">
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
            <div class="absolute inset-0 opacity-5">
                <img src="https://raw.githubusercontent.com/pattern-library/pattern-library/main/patterns/subtle-prism.svg" alt="" class="w-full h-full object-cover">
            </div>
            <div class="container mx-auto px-4 relative">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-16">Why Choose Eventure?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Easy Booking -->
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-2xl flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400 transform transition-transform duration-500 group-hover:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Easy Booking</h3>
                        <p class="text-gray-600 dark:text-gray-400">Simple and secure booking process with instant confirmation.</p>
                    </div>

                    <!-- Diverse Events -->
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-2xl flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400 transform transition-transform duration-500 group-hover:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Diverse Events</h3>
                        <p class="text-gray-600 dark:text-gray-400">From local meetups to international conferences, find your perfect event.</p>
                    </div>

                    <!-- Customer Support -->
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 rounded-2xl flex items-center justify-center transform transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400 transform transition-transform duration-500 group-hover:-rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
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
        <section class="py-20 bg-gradient-to-r from-teal-600 to-teal-700 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <img src="https://raw.githubusercontent.com/pattern-library/pattern-library/main/patterns/endless-clouds.svg" alt="" class="w-full h-full object-cover">
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
        </section> --}}

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
