<x-layouts.organisation :title="__('Organisation Profile')">
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
          <div class="border rounded-xl p-6 dark:border-gray-700 dark:bg-gray-800">
               <div class="flex justify-end mb-4">
                    <div class="flex gap-2">
                         <a href="#" class="p-2 border rounded-lg dark:border-gray-700 dark:hover:bg-gray-700">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                   <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                              </svg>
                         </a>
                         <a href="#" class="p-2 border rounded-lg dark:border-gray-700 dark:hover:bg-gray-700">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                   <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                              </svg>
                         </a>
                         <a href="#" class="p-2 border rounded-lg dark:border-gray-700 dark:hover:bg-gray-700">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                   <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                              </svg>
                         </a>
                    </div>
               </div>

               <div class="flex flex-col items-center">
                    <div class="relative mb-4">
                         <div class="w-24 h-24 rounded-full bg-blue-800 overflow-hidden border-2 border-teal-400 dark:border-teal-500">
                              <!-- Profile picture placeholder with mountain design -->
                              <div class="w-full h-full bg-slate-600 flex items-center justify-center dark:bg-slate-700">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-slate-500 dark:text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6 22.5L18 22.5L12 10.5L6 22.5Z M3 18L7.5 10.5L12 18L3 18Z" />
                                   </svg>
                              </div>
                         </div>
                         <div class="absolute bottom-0 right-0 bg-teal-400 rounded-full p-1 dark:bg-teal-500">
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                                   <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                              </svg>
                         </div>
                    </div>

                    <h2 class="text-xl font-semibold dark:text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600 mt-1 dark:text-gray-400">Timezone - <span id="user-timezone">Detecting...</span></p>
               </div>

               <div class="mt-8 flex justify-center">
                    <a href="#" class="text-teal-500 flex items-center gap-1 dark:text-teal-400 hover:text-teal-600 dark:hover:text-teal-300">
                         View Public Profile
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                              <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                              <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                         </svg>
                    </a>
               </div>
          </div>
     </div>

     @push('scripts')
     <script>
          // Function to detect and set timezone
          function detectAndSetTimezone() {
               try {
                    // Get timezone using browser's Intl API
                    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    document.getElementById('user-timezone').textContent = timezone;
               } catch (error) {
                    console.error('Failed to detect timezone:', error);
                    document.getElementById('user-timezone').textContent = 'Unknown';
               }
          }

          // Run on initial page load
          document.addEventListener('DOMContentLoaded', detectAndSetTimezone);

          // Also run when navigating via Livewire
          document.addEventListener('livewire:navigated', detectAndSetTimezone);
     </script>
     @endpush
</x-layouts.organisation>
