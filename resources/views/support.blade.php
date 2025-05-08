<x-layouts.public>
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Help & Support</h1>

            <div class="space-y-8">
                <!-- FAQs Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h2>
                    <div class="space-y-4">
                        <div class="border-b dark:border-gray-700 pb-4">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">How do I create an event?</h3>
                            <p class="text-gray-600 dark:text-gray-400">To create an event, first register as an organizer. Once registered, you can access your dashboard and click on the "Create Event" button to start setting up your event details.</p>
                        </div>
                        <div class="border-b dark:border-gray-700 pb-4">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">How do I purchase tickets?</h3>
                            <p class="text-gray-600 dark:text-gray-400">Browse available events, select the one you're interested in, and click "Get Tickets". Follow the checkout process to complete your purchase.</p>
                        </div>
                        <div class="pb-4">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-2">How can I contact support?</h3>
                            <p class="text-gray-600 dark:text-gray-400">You can reach our support team through:</p>
                            <ul class="list-disc list-inside mt-2 text-gray-600 dark:text-gray-400">
                                <li>Email: support@eventure.com</li>
                                <li>Phone: 1-800-EVENTURE</li>
                                <li>Live Chat: Available 24/7</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Support Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Contact Support</h2>
                    <form class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                            <textarea name="message" id="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
