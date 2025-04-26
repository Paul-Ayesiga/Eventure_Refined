<x-layouts.public>
    @if (isset($bookingId))
        @livewire('user.ticket-view', ['bookingId' => $bookingId])
    @elseif(isset($attendeeId))
        @livewire('user.ticket-view', ['attendeeId' => $attendeeId])
    @else
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No Ticket Information</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">We couldn't find the ticket you're looking for.</p>
                <a href="{{ route('user.events') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Browse Events
                </a>
            </div>
        </div>
    @endif
</x-layouts.public>
