<x-layouts.organisation :title="__('Reports')">
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
          <!-- Tabs Navigation -->
          <div class="border-b border-gray-200">
               <nav class="flex space-x-8">
                    <a href="#" class="border-b-2 border-primary-500 px-1 pb-4 text-sm font-medium text-primary-600">
                         Orders (0)
                    </a>
                    <a href="#" class="border-b-2 border-transparent px-1 pb-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                         Tickets (0)
                    </a>
                    <a href="#" class="border-b-2 border-transparent px-1 pb-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
                         Customers (0)
                    </a>
               </nav>
          </div>

          <!-- Empty State Content -->
          <div class="flex flex-1 flex-col items-center justify-center py-12">
               <div class="h-32 w-32 text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-full w-full">
                         <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75c-1.036 0-1.875-.84-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75C3.84 21.75 3 20.91 3 19.875v-6.75z" />
                    </svg>
               </div>
               <h3 class="mt-4 text-sm font-medium text-gray-900">No orders found</h3>
          </div>
     </div>
</x-layouts.organisation>
