<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Waiting List</h2>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <flux:button
                    wire:click="notifySelected"
                    variant="primary"
                    :disabled="empty($selectedEntries)"
                    icon="bell"
                >
                    Notify Selected
                </flux:button>
                <flux:button
                    wire:click="removeSelected"
                    variant="danger"
                    :disabled="empty($selectedEntries)"
                    icon="trash"
                    hint="select atleast one"
                >
                    Remove Selected
                </flux:button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex-1 w-full sm:max-w-xs">
                <flux:input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name, email, or phone..."
                    class="w-full"
                >
                    <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                </flux:input>
            </div>
            <div class="flex gap-2">
                <flux:button.group>
                    <flux:button
                        wire:click="$set('status', 'pending')"
                        variant="{{ $status === 'pending' ? 'primary' : 'outline' }}"
                    >
                        Pending
                    </flux:button>
                    <flux:button
                        wire:click="$set('status', 'notified')"
                        variant="{{ $status === 'notified' ? 'primary' : 'outline' }}"
                    >
                        Notified
                    </flux:button>
                    <flux:button
                        wire:click="$set('status', 'converted')"
                        variant="{{ $status === 'converted' ? 'primary' : 'outline' }}"
                    >
                        Converted
                    </flux:button>
                </flux:button.group>
            </div>
        </div>

        <flux:separator />
        @if($tickets->isEmpty())
            <div class="text-center py-8">
                <flux:icon name="user-group" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No waiting list entries</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($search)
                        No entries match your search criteria.
                    @else
                        There are currently no people on the waiting list for any tickets.
                    @endif
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($tickets as $ticket)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $ticket->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $ticket->getWaitingListCount() }} people waiting
                                </p>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $ticket->getRemainingQuantity() }} tickets available
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">
                                            {{-- <flux:checkbox
                                                wire:model.live="selectedEntries"
                                                value="all"
                                                class="rounded"
                                            /> --}}
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('user.name')">
                                            <div class="flex items-center gap-1">
                                                Name
                                                @if($sortField === 'user.name')
                                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="h-4 w-4" />
                                                @endif
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('quantity_requested')">
                                            <div class="flex items-center gap-1">
                                                Quantity
                                                @if($sortField === 'quantity_requested')
                                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="h-4 w-4" />
                                                @endif
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                                            <div class="flex items-center gap-1">
                                                Joined
                                                @if($sortField === 'created_at')
                                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="h-4 w-4" />
                                                @endif
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($ticket->waitingList as $entry)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <flux:checkbox
                                                    wire:model.live="selectedEntries"
                                                    value="{{ $entry->id }}"
                                                    class="rounded"
                                                />
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $entry->user->name }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->user->email }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->user->userDetail?->phone_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->quantity_requested }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @switch($entry->status)
                                                    @case('pending')
                                                        <flux:badge variant="warning">Pending</flux:badge>
                                                        @break
                                                    @case('notified')
                                                        <flux:badge variant="info">Notified</flux:badge>
                                                        @break
                                                    @case('converted')
                                                        <flux:badge variant="success">Converted</flux:badge>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
