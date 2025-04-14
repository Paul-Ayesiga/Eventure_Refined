<x-layouts.organisation :title="__('Contacts')">
     <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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
        <!-- Tabs navigation -->
        <div class="border-b flex">
            <a href="{{ route('contacts') }}" class="px-6 py-3 border-b-2 border-teal-500 text-teal-500 font-medium">Contacts</a>
            <a href="#" class="px-6 py-3 text-gray-600">Contact Lists</a>
        </div>

        <!-- Empty state content -->
        <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
            <!-- Contact illustration -->
            <div class="mb-6">
                <img src="{{ asset('images/contacts-illustration.svg') }}" alt="Contacts" class="w-40 h-40">
            </div>

            <h2 class="text-2xl font-semibold mb-4">Add Contacts</h2>

            <p class="text-gray-600 max-w-md mb-8">
                To add a contact, click on the 'Add a contact' button and
                manually enter their information or import a CSV file.
            </p>

            <div class="flex flex-col items-center gap-4">
                <a href="#" class="px-6 py-3 bg-teal-500 text-white rounded-md hover:bg-teal-600 transition">
                    Import CSV file
                </a>

                <div class="text-gray-500">OR</div>

                <a href="#" class="text-teal-500 hover:underline">
                    Add a contact
                </a>
            </div>
        </div>
     </div>
</x-layouts.organisation>
