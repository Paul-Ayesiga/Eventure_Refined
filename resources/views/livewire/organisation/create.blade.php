<div>
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Create Your Organization</h2>
        <p class="mb-6 text-gray-600 dark:text-gray-300">
            Creating an organization allows you to become an organizer and start creating events.
        </p>

        <form wire:submit.prevent="store" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Organization Name -->
                <div>
                    <flux:label for="name" required>Organization Name</flux:label>
                    <flux:input id="name" wire:model="name" type="text" class="mt-1 w-full" required />
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <flux:label for="email" required>Email Address</flux:label>
                    <flux:input id="email" wire:model="email" type="email" class="mt-1 w-full" required />
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <flux:label for="phone_number" required>Phone Number</flux:label>
                    <flux:input id="phone_number" wire:model="phone_number" type="text" class="mt-1 w-full"
                        required />
                    @error('phone_number')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Website -->
                <div>
                    <flux:label for="website">Website</flux:label>
                    <flux:input id="website" wire:model="website" type="url" class="mt-1 w-full"
                        placeholder="https://example.com" />
                    @error('website')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <flux:label for="country" required>Country</flux:label>
                    <flux:select id="country" wire:model="country" class="mt-1 w-full" required>
                        <option value="">Select a country</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </flux:select>
                    @error('country')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <flux:label for="currency" required>Currency</flux:label>
                    <flux:select id="currency" wire:model="currency" class="mt-1 w-full">
                        @foreach ($currencies as $code => $name)
                            <flux:select.option value="{{ $code }}">{{ $name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    @error('currency')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Logo Upload -->
            <div>
                <flux:label for="logo">Organization Logo</flux:label>
                <div class="mt-1 flex items-center">
                    <input type="file" wire:model="logo" id="logo" class="hidden" accept="image/*" />
                    <label for="logo"
                        class="cursor-pointer px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Choose File
                    </label>
                    <span class="ml-3 text-sm text-gray-500">
                        @if ($logo)
                            {{ $logo->getClientOriginalName() }}
                        @else
                            No file chosen
                        @endif
                    </span>
                </div>
                @error('logo')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror

                <div class="mt-3">
                    <div
                        class="relative h-32 w-32 rounded-md overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <!-- Loading spinner -->
                        <div wire:loading wire:target="logo"
                            class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 bg-opacity-75 z-10">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        <!-- Logo preview or placeholder -->
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo Preview"
                                class="h-full w-full object-cover">
                        @else
                            <div
                                class="h-full w-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload a square image for best results</p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <flux:label for="description">Description</flux:label>
                <flux:textarea id="description" wire:model="description" rows="4" class="mt-1 w-full">
                </flux:textarea>
                @error('description')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">Create Organization</flux:button>
            </div>
        </form>
    </div>
</div>
