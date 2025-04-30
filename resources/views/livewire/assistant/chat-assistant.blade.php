<div>
    <!-- Chat Button -->
    <div class="fixed bottom-6 right-6 z-50" x-data="{
        showTooltip: @entangle('showTooltip'),
        isOpen: @entangle('isOpen')
    }">
        <!-- Optional tooltip -->
        <div x-show="showTooltip && !isOpen" x-transition
            class="absolute bottom-full mb-2 right-0 bg-white dark:bg-gray-800 p-3 rounded-lg shadow-lg text-sm max-w-xs">
            <p class="text-gray-800 dark:text-white">Need help? Chat with our AI assistant!</p>
            <button wire:click="dismissTooltip" class="text-xs text-teal-600 dark:text-teal-400 mt-2 hover:underline">
                Got it
            </button>
            <div class="absolute bottom-0 right-4 transform translate-y-1/2 rotate-45 w-2 h-2 bg-white dark:bg-gray-800">
            </div>
        </div>

        <!-- Chat button -->
        <button wire:click="toggleChat"
            class="rounded-full h-14 w-14 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 bg-teal-600 hover:bg-teal-700 text-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </button>
    </div>

    <!-- Chat Panel -->
    <div x-data="{ isOpen: @entangle('isOpen') }" x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4"
        class="fixed bottom-24 right-6 z-50 w-full max-w-md sm:max-w-lg bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden"
        style="max-height: 80vh; display: none;">
        <div class="flex flex-col h-[80vh] sm:h-[70vh]">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center text-white mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-white">Eventure Assistant</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">How can I help you today?</p>
                    </div>
                </div>
                <div>
                    <button wire:click="toggleChat"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Message area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="message-container" x-data="{
                scrollToBottom() {
                    this.$el.scrollTop = this.$el.scrollHeight;
                }
            }"
                x-init="scrollToBottom()" @message-added.window="scrollToBottom()">
                <!-- Messages -->
                @foreach ($messages as $message)
                    @if ($message['type'] === 'assistant')
                        <!-- AI message -->
                        <div class="flex items-start mb-4">
                            <div
                                class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center text-white mr-2 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-3 max-w-[80%] shadow-sm">
                                <p class="text-gray-800 dark:text-white text-sm">
                                    {!! nl2br(e($message['content'])) !!}
                                </p>
                            </div>
                        </div>
                    @elseif($message['type'] === 'user')
                        <!-- User message -->
                        <div class="flex items-start justify-end mb-4">
                            <div class="bg-teal-50 dark:bg-teal-900/30 rounded-lg p-3 max-w-[80%] shadow-sm">
                                <p class="text-gray-800 dark:text-white text-sm">
                                    {!! nl2br(e($message['content'])) !!}
                                </p>
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Typing indicator -->
                @if ($isTyping)
                    <div class="flex items-start mb-4">
                        <div
                            class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center text-white mr-2 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm max-w-[80%]">
                            <div class="flex items-center">
                                <div
                                    class="mr-3 bg-teal-100 dark:bg-teal-900/30 px-3 py-1 rounded-full flex items-center">
                                    <svg class="animate-spin h-4 w-4 text-teal-500 dark:text-teal-400 mr-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-teal-700 dark:text-teal-300">AI is
                                        thinking...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Suggested actions -->
                @if (count($messages) <= 2)
                    <div class="flex flex-wrap gap-2 ml-10">
                        @if ($loadingSuggestions)
                            <!-- Loading skeleton for suggestions -->
                            <div class="flex flex-wrap gap-2">
                                @for ($i = 0; $i < 3; $i++)
                                    <div
                                        class="bg-gray-100 dark:bg-gray-700 text-transparent rounded-full h-8 w-24 animate-pulse">
                                        <span class="invisible">Loading</span>
                                    </div>
                                @endfor
                            </div>
                        @else
                            @foreach ($suggestions as $suggestion)
                                <button wire:click="handleSuggestion('{{ $suggestion['action'] }}')"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                                    wire:target="handleSuggestion"
                                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-sm px-3 py-1 rounded-full transition-colors duration-200 flex items-center"
                                    @if ($processingAction === $suggestion['action']) disabled @endif>
                                    @if ($processingAction === $suggestion['action'])
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-teal-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    @endif
                                    {{ $suggestion['text'] }}
                                </button>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>

            <!-- Input area -->
            <div class="border-t dark:border-gray-700 p-4">
                <form wire:submit.prevent="sendMessage" class="flex items-center">
                    <input type="text" wire:model="userInput" wire:keydown.enter.prevent="sendMessage"
                        placeholder="Type your message..."
                        class="flex-1 border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-teal-500 dark:focus:ring-teal-400"
                        autocomplete="off" />
                    <button type="submit"
                        class="ml-2 rounded-full h-10 w-10 flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 relative">
                        <!-- Normal send icon (shown when not loading) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" wire:loading.class="hidden" wire:target="sendMessage">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>

                        <!-- Bouncing bubbles loader (shown when loading) -->
                        <div class="hidden items-center justify-center absolute inset-0"
                            wire:loading.class.remove="hidden" wire:loading.class.add="flex"
                            wire:target="sendMessage">
                            <div class="flex space-x-1">
                                <div class="h-1.5 w-1.5 bg-white rounded-full animate-bounce"></div>
                                <div class="h-1.5 w-1.5 bg-white rounded-full animate-bounce"
                                    style="animation-delay: 0.2s"></div>
                                <div class="h-1.5 w-1.5 bg-white rounded-full animate-bounce"
                                    style="animation-delay: 0.4s"></div>
                            </div>
                        </div>
                    </button>
                </form>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                    Powered by Eventure AI Assistant
                </div>
            </div>
        </div>
    </div>
</div>
