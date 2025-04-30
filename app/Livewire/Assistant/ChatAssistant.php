<?php

namespace App\Livewire\Assistant;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;

class ChatAssistant extends Component
{
    public $messages = [];
    public $userInput = '';
    public $isTyping = false;
    public $suggestions = [];
    public $showTooltip = false;
    public $isOpen = false;
    public $loadingSuggestions = false;
    public $processingAction = null;

    public function mount()
    {
        // Check if the user has seen the tooltip before
        $this->showTooltip = !session()->has('assistant_introduced');

        // Initialize with a welcome message
        $this->messages = [
            [
                'type' => 'assistant',
                'content' => 'Hi there! I\'m your Eventure assistant. I can help you find events, book tickets, or answer questions about the platform.',
                'timestamp' => now()->timestamp
            ]
        ];

        // Set initial suggestions based on context
        $this->setSuggestions();
    }

    public function dismissTooltip()
    {
        $this->showTooltip = false;
        session(['assistant_introduced' => true]);
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function setSuggestions()
    {
        $this->loadingSuggestions = true;

        // In a real implementation, you might fetch suggestions from an API
        // Simulate a short delay for loading state demonstration
        usleep(300000); // 300ms delay

        // Default suggestions
        $this->suggestions = [
            ['text' => 'Find events', 'action' => 'findEvents'],
            ['text' => 'Book tickets', 'action' => 'bookTickets'],
            ['text' => 'My bookings', 'action' => 'viewBookings'],
        ];

        // If we're on an event page, add event-specific suggestions
        if (request()->route() && str_contains(request()->route()->getName(), 'event')) {
            $this->suggestions[] = ['text' => 'Tell me about this event', 'action' => 'eventInfo'];
        }

        $this->loadingSuggestions = false;
    }

    public function sendMessage()
    {
        if (empty(trim($this->userInput))) {
            return;
        }

        // Add user message to the conversation
        $this->messages[] = [
            'type' => 'user',
            'content' => $this->userInput,
            'timestamp' => now()->timestamp,
            'id' => uniqid('msg_')
        ];

        // Clear input and store the message
        $userMessage = $this->userInput;
        $this->userInput = '';

        // Show typing indicator
        $this->isTyping = true;

        // Dispatch event to scroll to bottom
        $this->dispatch('message-added');

        // Get response from the AI service
        $this->getAIResponse($userMessage);
    }

    public function getAIResponse($userMessage)
    {
        try {
            // Build conversation history for context
            $conversationHistory = $this->buildConversationHistory();

            // Get AI response using Mistral
            $response = $this->getMistralResponse($userMessage, $conversationHistory);

            // Add the response to the conversation
            $this->isTyping = false;
            $this->messages[] = [
                'type' => 'assistant',
                'content' => $response,
                'timestamp' => now()->timestamp,
                'id' => uniqid('msg_')
            ];

            // Dispatch event to scroll to bottom
            $this->dispatch('message-added');
        } catch (\Exception $e) {
            // Log the error
            Log::error('AI Assistant Error: ' . $e->getMessage());

            // Add a fallback response
            $this->isTyping = false;
            $this->messages[] = [
                'type' => 'assistant',
                'content' => "I'm sorry, I'm having trouble connecting to my brain right now. Please try again in a moment.",
                'timestamp' => now()->timestamp,
                'id' => uniqid('msg_')
            ];

            // Dispatch event to scroll to bottom
            $this->dispatch('message-added');
        }
    }

    protected function buildConversationHistory()
    {
        // Skip the first welcome message and build conversation history
        $history = [];

        foreach (array_slice($this->messages, 1) as $message) {
            if ($message['type'] === 'user') {
                $history[] = ['role' => 'user', 'content' => $message['content']];
            } else {
                $history[] = ['role' => 'assistant', 'content' => $message['content']];
            }
        }

        return $history;
    }

    protected function getMistralResponse($userMessage, $conversationHistory)
    {
        // Get the system prompt
        $systemPrompt = view('prompts.assistant-system')->render();

        try {
            // Try with mistral-small-latest first
            // Instead of using withMessages(), let's use withPrompt() for the current message
            // and include the conversation history in the system prompt

            // Format previous conversation for context
            $conversationContext = '';
            if (!empty($conversationHistory)) {
                foreach ($conversationHistory as $message) {
                    $role = ucfirst($message['role']);
                    $conversationContext .= "{$role}: {$message['content']}\n\n";
                }
            }

            // Combine system prompt with conversation context
            $fullSystemPrompt = $systemPrompt;
            if (!empty($conversationContext)) {
                $fullSystemPrompt .= "\n\nPrevious conversation:\n" . $conversationContext;
            }

            // Make the API call
            $response = Prism::text()
                ->using(Provider::Mistral, 'mistral-small-latest')
                ->withSystemPrompt($fullSystemPrompt)
                ->withPrompt($userMessage)
                ->asText();



            return $response->text;
        } catch (\Exception $e) {
            // Log the error
            Log::error('Mistral error: ' . $e->getMessage());

            // No need to debug in production

            // Try a different approach as fallback
            try {
                $response = Prism::text()
                    ->using(Provider::Mistral, 'mistral-small-latest')
                    ->withSystemPrompt($systemPrompt)
                    ->withPrompt($userMessage)
                    ->asText();

                return $response->text;
            } catch (\Exception $e2) {
                Log::error('Fallback error: ' . $e2->getMessage());
                return "I'm sorry, I'm having trouble connecting to my brain right now. Please try again in a moment.";
            }
        }
    }

    public function handleSuggestion($action)
    {
        // Set the processing action to show loading state
        $this->processingAction = $action;

        // Set the appropriate message based on the action
        switch ($action) {
            case 'findEvents':
                $this->userInput = "I want to find events";
                break;
            case 'bookTickets':
                $this->userInput = "How do I book tickets?";
                break;
            case 'viewBookings':
                $this->userInput = "Show me my bookings";
                break;
            case 'eventInfo':
                $this->userInput = "Tell me more about this event";
                break;
        }

        // Send the message
        $this->sendMessage();

        // Reset the processing action
        $this->processingAction = null;
    }

    public function render()
    {
        return view('livewire.assistant.chat-assistant');
    }
}
