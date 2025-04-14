<x-layouts.organisation :title="__('Coupons')">
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
        <!-- Search and Create Button -->
        <div class="flex justify-between items-center mb-4">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-gray-400">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </span>
                <input type="text" class="pl-10 pr-4 py-2 w-[280px] border rounded-md" placeholder="Search" />
            </div>
            <div class="flex items-center gap-3">
                <flux:modal.trigger name="create-coupon">
                    <flux:button color="teal">
                        Create Coupon
                    </flux:button>
                </flux:modal.trigger>
                <flux:button icon="arrow-path" variant="subtle" />
            </div>
        </div>

        <!-- Coupons List -->
        <div class="border rounded-lg overflow-hidden">
            <!-- Coupon Item -->
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <!-- Coupon Code and Date -->
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-gray-100 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gray-500">
                                <path d="M2 9h20M2 15h20M6 3v18M18 3v18" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium">EB187812 - EB187812</h3>
                            <p class="text-sm text-gray-500">Feb 03, 2025 3:00 PM (EAT) - Feb 03, 2026 3:00 PM (EAT)</p>
                        </div>
                    </div>

                    <!-- Status & Actions -->
                    <div class="flex items-center gap-3">
                        <flux:switch wire:model.live="notifications" label="Enable coupon" />

                        <flux:dropdown position="bottom" align="end">

                            <flux:button icon="ellipsis-vertical" variant="subtle" />
                            <flux:menu>
                                <flux:menu.item icon="pencil">
                                    Edit
                                </flux:menu.item>
                                <flux:menu.item icon="trash" variant="danger">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>

                <flux:separator />

                <!-- Coupon Details -->
                <div class="grid grid-cols-4 gap-6 mt-6">
                    <!-- Status -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-md bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gray-500">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 8v4" />
                                <path d="M12 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                <p class="font-medium">Inactive</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Used -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-md bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gray-500">
                                <rect x="3" y="12" width="6" height="8" rx="1" />
                                <rect x="9" y="8" width="6" height="12" rx="1" />
                                <rect x="15" y="4" width="6" height="16" rx="1" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Used</p>
                            <p class="font-medium">0/100</p>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-md bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gray-500">
                                <circle cx="9" cy="15" r="1" />
                                <circle cx="15" cy="9" r="1" />
                                <line x1="9" y1="9" x2="15" y2="15" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Discount</p>
                            <p class="font-medium">10%</p>
                        </div>
                    </div>

                    <!-- Last Used -->
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-md bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-gray-500">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="6" x2="12" y2="12" />
                                <line x1="12" y1="12" x2="16" y2="14" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Last Used</p>
                            <p class="font-medium">N/A</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More coupon items would go here -->
        </div>
    </div>

    <!-- Create Coupon Modal -->
    <flux:modal name="create-coupon" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create Coupon</flux:heading>
                <flux:text class="mt-2">Fill in the details for the new coupon.</flux:text>
            </div>

            <flux:input label="Code" placeholder="Coupon code" required />
            <flux:input label="Discount" placeholder="10.00" type="number" required />
            <flux:select label="Discount Type" required>
                <flux:select.option value="percent">Percent(%)</flux:select.option>
                <flux:select.option value="fixed">Fixed($)</flux:select.option>
            </flux:select>
            <flux:input label="Discount End Time" type="date" required />
            <flux:input label="Time" type="time" required />

            <div class="flex">
                <flux:spacer />
                <flux:button type="button" variant="subtle" class="mr-2">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Create</flux:button>
            </div>
        </div>
    </flux:modal>

</x-layouts.organisation>
