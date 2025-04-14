<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Event Tickets</h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <flux:button icon="printer" wire:click="printTickets" variant="outline" class="w-full sm:w-auto">
                    Print Tickets
                </flux:button>
                <flux:button icon="arrow-down-tray" wire:click="downloadTickets" variant="primary"
                    class="w-full sm:w-auto">
                    Download Tickets
                </flux:button>
                <flux:modal.trigger name="share-tickets" class="w-full sm:w-auto">
                    <flux:button icon="share" variant="subtle" class="w-full">
                        Share Tickets
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>

        <div class="space-y-8" id="tickets-container">
            @foreach ($attendees as $attendee)
                <div
                    class="ticket-container bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg max-w-2xl mx-auto">
                    <!-- Ticket Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-teal-400 p-4 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold">{{ $event->name }}</h3>
                                <p class="text-sm opacity-90">{{ $event->organisation->name }}</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="px-2 py-1 text-sm bg-teal-500 bg-opacity-30 rounded">{{ $event->event_type ?: 'online' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Body -->
                    <div class="p-6 flex flex-col md:flex-row justify-between">
                        <!-- Left side: Event details -->
                        <div class="flex-1 pr-4">
                            <div class="mb-6">
                                <p class="text-sm text-gray-500">Coming soon</p>
                                <p class="font-semibold text-gray-400">{{ $event->venue ?: 'Online Event' }},
                                    {{ $event->location->country ?? '' }}</p>
                                <p class="text-sm font-medium text-gray-400">
                                    {{ $event->start_datetime->format('M d, Y, h:i A') }} ({{ $event->timezone }})
                                </p>
                            </div>

                            <div class="mb-6">
                                <p class="text-xs uppercase text-gray-500 font-medium">ISSUED TO</p>
                                <p class="font-semibold text-accent">{{ $attendee->first_name }} {{ $attendee->last_name }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs uppercase text-gray-500 font-medium">BOOKING REFERENCE</p>
                                    <p class="font-semibold text-accent">{{ $booking->booking_reference }}</p>
                                    <p class="text-xs text-gray-500 mt-3">
                                        Booked On<br>
                                        {{ $booking->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-gray-500 font-medium">TICKET</p>
                                    <p class="font-semibold text-accent">{{ $attendee->ticket->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $attendee->ticket->price > 0 ? number_format($attendee->ticket->price, 2) . ' ' . $event->currency : 'FREE' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right side: QR code -->
                        <div class="mt-6 md:mt-0 flex flex-col items-center justify-center">
                            <div class="qr-code-container bg-white p-2 border border-gray-200 rounded">
                                <img src="{{ $attendee->qrCode }}" alt="QR Code" class="w-40 h-40">
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Footer -->
                    <div class="bg-gray-50 p-3 text-center text-xs text-gray-500 border-t border-gray-200">
                        <p>© {{ date('Y') }} {{ $event->organisation->name }} - All Rights Reserved</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <flux:modal name="share-tickets" class="md:max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Share Ticket</flux:heading>
                <flux:text class="mt-2">Share this ticket with others via:</flux:text>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                <!-- WhatsApp -->
                <a href="https://wa.me/?text=Check%20out%20my%20ticket" id="shareWhatsApp" target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-green-500 text-white hover:bg-green-600 transition"
                    onclick="this.href='https://wa.me/?text=' + encodeURIComponent('Check out my ticket: ' + document.getElementById('shareUrl').value)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    <span class="text-xs">WhatsApp</span>
                </a>

                <!-- Email -->
                <a href="mailto:?subject=Event%20Ticket&body=Check%20out%20my%20ticket" id="shareEmail" target="_blank"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition"
                    onclick="this.href='mailto:?subject=' + encodeURIComponent('Event Ticket') + '&body=' + encodeURIComponent('Check out my ticket: ' + document.getElementById('shareUrl').value)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs">Email</span>
                </a>

                <!-- Facebook Messenger -->
                <a href="https://www.facebook.com/dialog/send?link=https%3A%2F%2Fexample.com&app_id=123456789&redirect_uri=https%3A%2F%2Fexample.com"
                    id="shareFacebook" target="_blank" rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                    onclick="this.href='https://www.facebook.com/dialog/send?link=' + encodeURIComponent(document.getElementById('shareUrl').value) + '&app_id=123456789&redirect_uri=' + encodeURIComponent(window.location.origin)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                    </svg>
                    <span class="text-xs">FB Messenger</span>
                </a>

                <!-- Twitter/X Direct Message -->
                <a href="https://twitter.com/messages/compose?text=Check%20out%20my%20ticket:%20https%3A%2F%2Fexample.com"
                    id="shareTwitter" target="_blank" rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-black text-white hover:bg-gray-800 transition"
                    onclick="this.href='https://twitter.com/messages/compose?text=' + encodeURIComponent('Check out my ticket: ' + document.getElementById('shareUrl').value)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z">
                        </path>
                    </svg>
                    <span class="text-xs">Twitter DM</span>
                </a>

                <!-- LinkedIn Message -->
                <a href="https://www.linkedin.com/messaging/compose/?body=Check%20out%20my%20ticket:%20https%3A%2F%2Fexample.com"
                    id="shareLinkedIn" target="_blank" rel="noopener noreferrer"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-blue-700 text-white hover:bg-blue-800 transition"
                    onclick="this.href='https://www.linkedin.com/messaging/compose/?body=' + encodeURIComponent('Check out my ticket: ' + document.getElementById('shareUrl').value)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
                    </svg>
                    <span class="text-xs">LinkedIn DM</span>
                </a>

                <!-- Copy Link -->
                <a href="javascript:void(0)" id="copyLink"
                    class="flex flex-col items-center justify-center p-3 rounded-lg bg-gray-500 text-white hover:bg-gray-600 transition"
                    onclick="copyToClipboard(document.getElementById('shareUrl').value); this.querySelector('.copy-feedback').classList.remove('hidden'); setTimeout(() => { this.querySelector('.copy-feedback').classList.add('hidden'); }, 2000);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs">Copy Link</span>
                    <span
                        class="copy-feedback hidden absolute bg-black text-white text-xs px-2 py-1 rounded -mt-8">Copied!</span>
                </a>
            </div>

            <div>
                <flux:text>Or share this link:</flux:text>
                <div class="flex flex-col sm:flex-row mt-2 gap-2 sm:gap-0">
                    <input type="text" id="shareUrl"
                        class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-r-none px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"
                        readonly>
                    <flux:button id="copyUrlButton" class="w-full sm:w-auto sm:rounded-l-none" variant="filled">Copy
                    </flux:button>
                </div>
            </div>

            <div class="flex justify-end">
                <flux:button wire:click="shareTickets" variant="primary" class="w-full sm:w-auto">Regenerate Share Link
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Print tickets functionality
            @this.on('print-tickets', () => {
                const printContents = document.getElementById('tickets-container').innerHTML;
                const originalContents = document.body.innerHTML;

                // Create a print-friendly version
                document.body.innerHTML = `
                    <style>
                        @media print {
                            body { margin: 0; padding: 20px; background-color: white; }
                            .ticket-container {
                                page-break-after: always;
                                max-width: 100%;
                                margin-bottom: 30px;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                overflow: hidden;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                            }
                            .qr-code-container {
                                border: 1px solid #e2e8f0;
                                padding: 8px;
                            }
                            .qr-code-container img {
                                width: 160px;
                                height: 160px;
                            }
                            .bg-gradient-to-r {
                                background: linear-gradient(to right, #3b82f6, #2dd4bf);
                                color: white;
                                padding: 16px;
                            }
                            .text-gray-400 {
                                color: #9ca3af;
                            }
                            .text-gray-500 {
                                color: #6b7280;
                            }
                            .bg-teal-500 {
                                background-color: rgba(20, 184, 166, 0.3);
                                border-radius: 4px;
                                padding: 2px 8px;
                            }
                            .grid {
                                display: grid;
                                grid-template-columns: 1fr 1fr;
                                gap: 24px;
                            }
                            .flex-col {
                                display: flex;
                                flex-direction: column;
                            }
                            .md\\:flex-row {
                                display: flex;
                                flex-direction: row;
                                justify-content: space-between;
                            }
                            .bg-gray-50 {
                                background-color: #f9fafb;
                                border-top: 1px solid #e5e7eb;
                                padding: 12px;
                                text-align: center;
                                font-size: 12px;
                                color: #6b7280;
                            }
                        }
                    </style>
                    <div>${printContents}</div>
                `;

                window.print();

                // Restore original content
                document.body.innerHTML = originalContents;

                // Reinitialize Livewire after restoring content
                window.Livewire.rescan();
            });

            // Share tickets functionality
            @this.on('share-tickets', (data) => {
                const shareUrl = document.getElementById('shareUrl');
                const shareUrlValue = data.url || window.location.href;

                // Set the share URL
                shareUrl.value = shareUrlValue;

                // All share buttons are now handled directly in the HTML with onclick

                // Copy link functionality is now handled directly in the HTML with onclick

                // Remove any existing event listeners from the copy button
                const copyUrlButton = document.getElementById('copyUrlButton');
                const newCopyUrlButton = copyUrlButton.cloneNode(true);
                copyUrlButton.parentNode.replaceChild(newCopyUrlButton, copyUrlButton);

                // Copy URL button
                newCopyUrlButton.addEventListener('click', () => {
                    copyToClipboard(shareUrl.value);
                    newCopyUrlButton.textContent = 'Copied!';
                    setTimeout(() => {
                        newCopyUrlButton.textContent = 'Copy';
                    }, 2000);
                });
            });

            // Helper function to copy text to clipboard
            async function copyToClipboard(text) {
                try {
                    // Try to use the modern Clipboard API
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(text);
                        return;
                    }

                    // Fallback to the older method
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                } catch (err) {
                    console.error('Failed to copy text: ', err);
                }
            }
        });
    </script>
@endpush
