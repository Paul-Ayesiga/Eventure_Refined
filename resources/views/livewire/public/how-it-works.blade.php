<div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-teal-700 to-teal-00 text-white py-20 relative">
        <div class="absolute inset-0 opacity-50">
            <img src="https://images.unsplash.com/photo-1519677100203-a0e668c92439?q=80&w=2070" alt="Party Background" class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4">How Eventure Works</h1>
                <p class="text-xl text-teal-100">Create, manage, and promote your events in just a few simple steps</p>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <!-- Step 1 -->
            <div class="flex flex-col md:flex-row items-center mb-16 gap-8">
                <div class="md:w-1/2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                        <img src="{{ asset('images/create-event.jpg') }}" alt="Create Event" class="w-full h-auto">
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="flex items-center mb-4">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-600 text-white font-semibold mr-3">1</span>
                        <h3 class="text-2xl font-bold dark:text-white">Create Your Event</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">Set up your event in minutes with our intuitive event creation form. Add all the essential details:</p>
                    <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-300">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Event name and description
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Date and time
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Location or virtual event link
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col md:flex-row-reverse items-center mb-16 gap-8">
                <div class="md:w-1/2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                        <img src="{{ asset('images/customize-tickets.jpg') }}" alt="Customize Tickets" class="w-full h-auto">
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="flex items-center mb-4">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-600 text-white font-semibold mr-3">2</span>
                        <h3 class="text-2xl font-bold dark:text-white">Customize Tickets</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">Design your ticket types and set up pricing:</p>
                    <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-300">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Multiple ticket tiers
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Early bird discounts
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Promotional codes
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col md:flex-row items-center mb-16 gap-8">
                <div class="md:w-1/2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                        <img src="{{ asset('images/promote-event.jpeg') }}" alt="Promote Event" class="w-full h-auto">
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="flex items-center mb-4">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-600 text-white font-semibold mr-3">3</span>
                        <h3 class="text-2xl font-bold dark:text-white">Promote Your Event</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">Share your event with built-in marketing tools:</p>
                    <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-300">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Social media integration
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Email marketing
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Custom event page
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col md:flex-row-reverse items-center gap-8">
                <div class="md:w-1/2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
                        <img src="{{ asset('images/manage-event.jpeg') }}" alt="Manage Event" class="w-full h-auto">
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="flex items-center mb-4">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-600 text-white font-semibold mr-3">4</span>
                        <h3 class="text-2xl font-bold dark:text-white">Manage Your Event</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">Track and manage your event with powerful tools:</p>
                    <ul class="mt-4 space-y-2 text-gray-600 dark:text-gray-300">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Real-time analytics
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Attendee management
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mobile check-in
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-teal-600 to-blue-600 text-white py-16 relative">
        <div class="absolute inset-0 opacity-70">
            <img src="https://images.unsplash.com/photo-1519677100203-a0e668c92439?q=80&w=2070" alt="Party Background" class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4 text-center relative z-10 ">
            <h2 class="text-3xl font-bold mb-4">Ready to Create Your Event?</h2>
            <p class="text-xl text-teal-100 mb-8">Join thousands of event organizers who trust Eventure</p>
            <a href="{{ route('register') }}" class="inline-block bg-transparent text-teal-200 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 hover:text-teal-600 transition duration-150 border-2">Get Started for Free</a>
        </div>
    </div>
</div>
