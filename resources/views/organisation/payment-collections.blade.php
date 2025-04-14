<x-layouts.organisation :title="__('Payment Collection')">
     <!-- Gradient background strip -->
    <div class="absolute top-0 left-0 right-0 w-full h-32 bg-gradient-to-r from-cyan-100 to-purple-100"></div>

    <div class="relative flex h-full w-full flex-col gap-4 rounded-xl">
        <flux:breadcrumbs class="mb-5">
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

        <!-- Main content container -->
        <div class="relative max-w-2xl mx-auto">
            <div class="flex h-full w-full flex-1 flex-col items-center justify-center gap-4 p-8 bg-white rounded-2xl shadow-lg">
                <h1 class="text-4xl font-bold text-center text-black">stripe</h1>
                <p class="text-xl text-center text-gray-700">Get paid for your events</p>
                <p class="text-center text-gray-600 max-w-md">Connect your Stripe account to receive payments directly. Stripe is fast and secure. If you don't have a Stripe account, click the button below to create one.</p>
                <button class="mt-4 px-6 py-3 bg-teal-500 text-white rounded-md font-medium hover:bg-teal-600 transition-colors">Connect with Stripe</button>
                <p class="mt-4 text-sm text-center text-gray-600">
                    Don't see your preferred payment method? Contact our support team at
                    <a href="mailto:contact@eventbookings.com" class="text-teal-500 hover:text-teal-600">contact@eventbookings.com</a>
                    to explore your options.
                </p>
            </div>
        </div>
    </div>
</x-layouts.organisation>
