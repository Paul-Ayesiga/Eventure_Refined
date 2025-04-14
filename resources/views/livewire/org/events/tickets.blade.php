<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Event Tickets</h2>
            <flux:button icon="plus" wire:click="openCreateModal" variant="primary" class="bg-teal-500">
                Create Ticket
            </flux:button>
        </div>

        @if ($tickets->isEmpty())
            <div class="text-center py-8">
                <flux:icon name="ticket" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tickets</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new ticket.</p>
                <div class="mt-6">
                    <flux:button icon="plus" wire:click="openCreateModal" variant="primary" class="bg-teal-500">
                        Create Ticket
                    </flux:button>
                </div>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Price</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Available</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Sale Period</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->name }}
                                    </div>
                                    @if ($ticket->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                            {{ $ticket->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ number_format($ticket->price, 2) }} {{ $event->currency }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $ticket->getRemainingQuantity() }} / {{ $ticket->quantity_available }}</div>
                                    @if ($ticket->getWaitingListCount() > 0)
                                        <div class="text-sm text-yellow-600 dark:text-yellow-400">
                                            {{ $ticket->getWaitingListCount() }} on waiting list
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $ticket->sale_start_date->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">to
                                        {{ $ticket->sale_end_date->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <flux:button.group>
                                            <flux:button size="sm" icon="pencil"
                                                wire:click="openEditModal({{ $ticket->id }})" variant="primary">
                                            </flux:button>
                                            <flux:button size="sm"
                                                wire:click="toggleTicketStatus({{ $ticket->id }})" variant="subtle">
                                                <flux:icon
                                                    name="{{ $ticket->status === 'active' ? 'eye-slash' : 'eye' }}" />
                                            </flux:button>
                                            <flux:modal.trigger :name="'delete-ticket-'.$ticket->id">
                                                <flux:button variant="danger" icon="trash" size="sm">
                                                </flux:button>
                                            </flux:modal.trigger>

                                        </flux:button.group>

                                    </div>
                                </td>
                            </tr>
                            <flux:modal :name="'delete-ticket-'.$ticket->id" class="min-w-[22rem]">
                                <div class="space-y-6">
                                    <div>
                                        <flux:heading size="lg">Delete Ticket?</flux:heading>

                                        <flux:text class="mt-2">
                                            <p>You're about to delete this ticket.</p>
                                            <p>This action cannot be reversed.</p>
                                        </flux:text>
                                    </div>

                                    <div class="flex gap-2">
                                        <flux:spacer />

                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancel</flux:button>
                                        </flux:modal.close>

                                        <flux:button variant="danger" wire:click="deleteTicket({{ $ticket->id }})"
                                            class="cursor-pointer">Delete Ticket</flux:button>
                                    </div>
                                </div>
                            </flux:modal>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                @foreach ($tickets as $ticket)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->name }}
                                </div>
                                @if ($ticket->description)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->description }}
                                    </div>
                                @endif
                            </div>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-2">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Price</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($ticket->price, 2) }} {{ $event->currency }}
                                </div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Available</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $ticket->getRemainingQuantity() }} / {{ $ticket->quantity_available }}
                                    @if ($ticket->getWaitingListCount() > 0)
                                        <div class="text-sm text-yellow-600 dark:text-yellow-400">
                                            {{ $ticket->getWaitingListCount() }} on waiting list
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Sale Period</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $ticket->sale_start_date->format('M d, Y') }} to
                                    {{ $ticket->sale_end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-2">
                            <flux:button.group>
                                <flux:button size="sm" icon="pencil"
                                    wire:click="openEditModal({{ $ticket->id }})" variant="primary">
                                </flux:button>
                                <flux:button size="sm" wire:click="toggleTicketStatus({{ $ticket->id }})"
                                    variant="subtle">
                                    <flux:icon name="{{ $ticket->status === 'active' ? 'eye-slash' : 'eye' }}" />
                                </flux:button>
                                <flux:modal.trigger :name="'delete-ticket-'.$ticket->id">
                                    <flux:button variant="danger" icon="trash" size="sm"></flux:button>
                                </flux:modal.trigger>
                            </flux:button.group>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

    <!-- Ticket Modal -->
    <flux:modal wire:model="isModalOpen" class="max-w-3xl" variant="flyout">
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $isEditing ? 'Edit Ticket' : 'Create Ticket' }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $isEditing ? 'Update the ticket details below.' : 'Fill in the details to create a new ticket.' }}
                </p>
            </div>

            <form wire:submit.prevent="saveTicket">
                <div class="space-y-4">
                    <!-- Ticket Name -->
                    <div>
                        <flux:label for="name" required>Ticket Name</flux:label>
                        <flux:input id="name" wire:model="name" type="text" class="mt-1 w-full"
                            placeholder="e.g. VIP, Regular, Early Bird" />
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <flux:label for="description">Description</flux:label>
                        <flux:textarea id="description" wire:model="description" class="mt-1 w-full" rows="3"
                            placeholder="Describe what this ticket includes"></flux:textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <flux:label for="price" required>Price</flux:label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ $event->currency }}</span>
                            </div>
                            <flux:input id="price" wire:model="price" type="number" step="0.01"
                                min="0" class="pl-12 w-full" placeholder="0.00" />
                        </div>
                        @error('price')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Quantity Available -->
                    <div>
                        <flux:label for="quantity_available" required>Quantity Available</flux:label>
                        <flux:input id="quantity_available" wire:model="quantity_available" type="number"
                            min="1" class="mt-1 w-full" placeholder="100" />
                        @error('quantity_available')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Sale Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <flux:label for="sale_start_date" required>Sale Start Date</flux:label>
                            <flux:input id="sale_start_date" wire:model="sale_start_date" type="date"
                                class="mt-1 w-full" />
                            @error('sale_start_date')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <flux:label for="sale_end_date" required>Sale End Date</flux:label>
                            <flux:input id="sale_end_date" wire:model="sale_end_date" type="date"
                                class="mt-1 w-full" />
                            @error('sale_end_date')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Max Tickets Per Booking -->
                    <div>
                        <flux:label for="max_tickets_per_booking" required>Max Tickets Per Booking</flux:label>
                        <flux:input id="max_tickets_per_booking" wire:model="max_tickets_per_booking" type="number"
                            min="1" class="mt-1 w-full" placeholder="1" />
                        @error('max_tickets_per_booking')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <flux:label for="status" required>Status</flux:label>
                        <flux:select id="status" wire:model="status" class="mt-1 w-full">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </flux:select>
                        @error('status')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Repeat Ticket - Only show if event has repeat days -->
                    @if ($event->event_repeat !== 'Does not repeat' && $event->repeat_days > 0)
                        <div>
                            <div class="flex items-center">
                                <flux:checkbox id="repeat_ticket" wire:model="repeat_ticket" />
                                <flux:label for="repeat_ticket" class="ml-2">Repeat ticket for all
                                    {{ $event->repeat_days }} days</flux:label>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                When enabled, this ticket will be available for all days of the event.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <flux:button type="button" wire:click="closeModal" variant="outline">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="bg-teal-500">
                        {{ $isEditing ? 'Update Ticket' : 'Create Ticket' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
