<x-layouts.organisation :title="__('Coupons')">
    <div class="flex h-full w-full flex-1 flex-col items-center justify-center gap-4 rounded-xl">
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
        <div class="flex flex-col items-center justify-center text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-44 w-44 mb-4" viewBox="0 0 64 64">
                <circle cx="32" cy="32" r="30" fill="rgba(0, 204, 204, 0.1)" />
                <rect x="10" y="20" width="44" height="24" rx="4" fill="white" stroke="#ccc" stroke-width="2" />
                <line x1="14" y1="28" x2="50" y2="28" stroke="#333" stroke-width="2" />
                <line x1="14" y1="32" x2="50" y2="32" stroke="#ccc" stroke-width="2" />
                <line x1="14" y1="36" x2="50" y2="36" stroke="#ccc" stroke-width="2" />
                <circle cx="53" cy="33" r="6" fill="#004d4d" />
                <text x="53" y="36" fill="white" font-size="7" font-family="Arial" text-anchor="middle">&lt;/&gt;</text>
            </svg>
            <p class="text-lg font-medium">You haven't set up any tracking codes.</p>
            <p class="text-sm text-gray-500">Click the "Add Tracking Code" button to connect your first tracking code.</p>
            <button class="mt-4 px-4 py-2 bg-teal-500 text-white rounded">Add Tracking Code</button>
        </div>
    </div>
</x-layouts.organisation>
