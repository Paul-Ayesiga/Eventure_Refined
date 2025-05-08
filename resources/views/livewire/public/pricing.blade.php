<div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-700 to-indigo-00 text-white py-20 relative">
        <div class="absolute inset-0 opacity-50">
            <img src="https://images.unsplash.com/photo-1519677100203-a0e668c92439?q=80&w=2070" alt="Party Background" class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl font-bold mb-4">Simple, Transparent Pricing</h1>
                <p class="text-xl text-teal-100">Choose the perfect plan for your event management needs</p>
            </div>
        </div>
    </div>

    <!-- Pricing Toggle -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex justify-center items-center space-x-4 mb-12">
            <span class="text-lg font-medium {{ $billingFrequency === 'monthly' ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">Monthly</span>
            <button wire:click="$set('billingFrequency', $billingFrequency === 'monthly' ? 'yearly' : 'monthly')" type="button" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 {{ $billingFrequency === 'yearly' ? 'bg-teal-600' : 'bg-gray-200' }}">
                <span class="sr-only">Toggle Billing Frequency</span>
                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $billingFrequency === 'yearly' ? 'translate-x-5' : 'translate-x-0' }}"></span>
            </button>
            <span class="text-lg font-medium {{ $billingFrequency === 'yearly' ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">Yearly</span>
            <span class="ml-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Save 20%</span>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            <!-- Free Plan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Free</h3>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">Perfect for getting started with event management</p>
                    <p class="mt-8">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">$0</span>
                        <span class="text-gray-500 dark:text-gray-400">/month</span>
                    </p>

                    <!-- Feature List -->
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Up to 300 tickets per event</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Basic analytics</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Email support</span>
                        </li>
                    </ul>
                </div>

                <div class="px-8 pb-8">
                    <a href="{{ route('register') }}" class="block w-full bg-teal-600 text-center py-3 rounded-md text-white font-semibold hover:bg-teal-700 transition duration-150">Get Started</a>
                </div>
            </div>

            <!-- Pro Plan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-2 border-teal-500">
                <div class="p-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Pro</h3>
                        <span class="px-3 py-1 text-xs font-semibold text-teal-700 bg-teal-100 rounded-full">Popular</span>
                    </div>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">Advanced features for growing events</p>
                    <p class="mt-8">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">${{ $billingFrequency === 'monthly' ? '49' : '39' }}</span>
                        <span class="text-gray-500 dark:text-gray-400">/month</span>
                    </p>

                    <!-- Feature List -->
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Unlimited tickets</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Advanced analytics</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Priority support</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Custom branding</span>
                        </li>
                    </ul>
                </div>

                <div class="px-8 pb-8">
                    <a href="{{ route('register') }}" class="block w-full bg-teal-600 text-center py-3 rounded-md text-white font-semibold hover:bg-teal-700 transition duration-150">Get Started</a>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-8">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Enterprise</h3>
                    <p class="mt-4 text-gray-500 dark:text-gray-400">Custom solutions for large organizations</p>
                    <p class="mt-8">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">Custom</span>
                    </p>

                    <!-- Feature List -->
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Everything in Pro</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Dedicated account manager</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">Custom integrations</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-3 text-gray-700 dark:text-gray-300">SLA agreement</span>
                        </li>
                    </ul>
                </div>

                <div class="px-8 pb-8">
                    <a href="{{ route('contact') }}" class="block w-full bg-gray-800 dark:bg-gray-700 text-center py-3 rounded-md text-white font-semibold hover:bg-gray-900 dark:hover:bg-gray-600 transition duration-150">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12 dark:text-white">Frequently Asked Questions</h2>
            <div class="space-y-8">
                <div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">What payment methods do you accept?</h3>
                    <p class="text-gray-600 dark:text-gray-300">We accept all major credit cards including Visa, Mastercard, and American Express. We also support payment through PayPal.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">Can I change plans later?</h3>
                    <p class="text-gray-600 dark:text-gray-300">Yes, you can upgrade or downgrade your plan at any time. Changes will be reflected in your next billing cycle.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-white">What happens when I exceed ticket limits?</h3>
                    <p class="text-gray-600 dark:text-gray-300">On the Free plan, you'll need to upgrade to continue selling tickets beyond the limit. Pro and Enterprise plans have no ticket limits.</p>
                </div>
            </div>
        </div>
    </div>
</div>
