<x-layouts.organisation :title="__('My Team')">
     <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs class="mb-10">
            @php
                $breadcrumbs = getBreadcrumbs();
            @endphp
            @foreach ($breadcrumbs as $index => $crumb)
                @if ($index < count($breadcrumbs) - 1)
                    <flux:breadcrumbs.item href="{{ $crumb['url'] }}">
                        {{ $crumb['title'] }}
                    </flux:breadcrumbs.item>
                @else
                    <flux:breadcrumbs.item>
                        {{ $crumb['title'] }}
                    </flux:breadcrumbs.item>
                @endif
            @endforeach
        </flux:breadcrumbs>
        <!-- Tab Navigation -->
        <div class="border-b">
            <nav class="flex">
                <a href="#" class="px-4 py-2 text-green-500 border-b-2 border-green-500 font-medium">
                    {{ __('Members') }}
                </a>
                <a href="#" class="px-4 py-2 text-gray-500 hover:text-gray-700">
                    {{ __('Manage Roles') }}
                </a>
            </nav>
        </div>

        <!-- Search and Actions Bar -->
        <div class="flex justify-between items-center">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" class="pl-10 py-2 pr-3 border border-gray-300 rounded-md w-64" placeholder="{{ __('Search by name or email') }}">
            </div>

            <div class="flex gap-2">
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">
                    {{ __('Send Invitation') }}
                </button>
                <button class="bg-gray-100 hover:bg-gray-200 p-2 rounded-md">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Team Members List Container -->
        <div class="mt-4">
            <!-- Empty State Placeholder -->
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="bg-gray-100 p-4 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ __('No team members yet') }}</h3>
                <p class="text-gray-500 max-w-md">
                    {{ __('Invite members to your team to collaborate and work together on your events.') }}
                </p>
            </div>
        </div>
     </div>
</x-layouts.organisation>
