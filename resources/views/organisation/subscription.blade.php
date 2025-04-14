<x-layouts.organisation :title="__('Subscription')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <flux:breadcrumbs>
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

         <!-- Events list -->

        <h1 class="font-bold font-sans text-3xl mt-5">Upgrade Events to Premium</h1>
        <h5 class="text-sm mt-[-12px] font-light">Get access to premium features, extended event limits</h5>

        <div class=" mt-10 dark:bg-gray-900 bg-white rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg">
            <div class="p-4 border-b dark:border-gray-800">
                <div class="flex items-start space-x-4">
                    <div class="w-24 h-16 sm:w-32 sm:h-20 flex-shrink-0 rounded-md overflow-hidden">
                        <img src="https://via.placeholder.com/128x80" alt="Event image" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">Test Event Name</h3>
                            <button class="mt-2 sm:mt-0 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Upgrade</button>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Venue Event Location
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <flux:separator />
        <!-- Add-ons Pricing Section -->

        <!-- component -->
        <div class="w-full flex flex-col items-center justify-center min-h-screen p-10 text-gray-700 md:p-20 left-0 right-0">
            <h2 class="text-2xl font-medium">Add-Ons</h2>

            <!-- Component Start -->
            <div class="flex flex-wrap items-center justify-center w-full max-w-4xl mt-8">
                <div class="flex flex-col flex-grow mt-8 overflow-hidden bg-white rounded-lg shadow-lg">
                    <div class="flex flex-col items-center p-10 bg-gray-200">
                        <span class="font-semibold">My Team</span>
                        <div class="flex items-center">
                            <span class="text-3xl">$</span>
                            <span class="text-5xl font-bold">20</span>
                            <span class="text-2xl text-gray-500">/mo</span>
                        </div>
                    </div>
                    <div class="p-10">
                      <ul>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-2 flex-wrap whitespace-normal break-words max-w-[150px]">
                                Create roles and add team members to manage events
                            </span>
                        </li>
                      </ul>
                    </div>
                    <div class="flex px-10 pb-10 justfy-center">
                        <flux:button class="flex items-center justify-center w-full h-12 px-6 text-sm uppercase bg-gray-600 rounded-lg">Upgrade Now</flux:button>
                    </div>
                </div>

                <!-- Tile 2 -->
                <div class="z-10 flex flex-col flex-grow mt-8 overflow-hidden transform bg-white rounded-lg shadow-lg md:scale-110">
                    <div class="flex flex-col items-center p-10 bg-gray-200">
                        <span class="font-semibold">API</span>
                        <div class="flex items-center">
                            <span class="text-3xl">$</span>
                            <span class="text-6xl font-bold">50</span>
                            <span class="text-2xl text-gray-500">/mo</span>
                        </div>
                    </div>
                    <div class="p-10">
                        <ul>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-2 flex-wrap whitespace-normal break-words max-w-[150px]">
                                    Unlock API features to intergrate and enhance your event management experience                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex px-10 pb-10 justfy-center">
                        <flux:button class="flex items-center justify-center w-full h-12 px-6 text-sm uppercase bg-gray-600 rounded-lg">Upgrade Now</flux:button>
                    </div>
                </div>

                <!-- Tile 3 -->
                <div class="flex flex-col flex-grow overflow-hidden bg-white rounded-lg shadow-lg mt-19">
                    <div class="flex flex-col items-center p-10 bg-gray-200">
                        <span class="font-semibold">Email Pack</span>
                        <div class="flex items-center">
                            <span class="text-3xl">$</span>
                            <span class="text-5xl font-bold">99</span>
                            <span class="text-2xl text-gray-500">/pack</span>
                        </div>
                    </div>
                    <div class="p-10">
                        <ul>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-2 flex-wrap whitespace-normal break-words max-w-[150px]">
                                    Add 50,000 emails to your events for email campaigns.
                                </span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex px-10 pb-10 justfy-center">
                        <flux:button class="flex items-center justify-center w-full h-12 px-6 text-sm uppercase bg-gray-600 rounded-lg">Get Now</flux:button>
                    </div>
                </div>
            </div>
            <!-- Component End  -->

        </div>

    </div>
</x-layouts.organisation>
