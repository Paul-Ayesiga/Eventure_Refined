<?php

namespace App\Livewire\Assistant;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Agents\EventureAssistant;
use NeuronAI\Chat\Messages\UserMessage;

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
        try {
            // Get the Neuron AI agent from the container
            $agent = app(EventureAssistant::class);

            // Convert conversation history to Neuron AI message format
            $messages = [];

            // Add previous messages from conversation history
            foreach ($conversationHistory as $message) {
                if ($message['role'] === 'user') {
                    $messages[] = new UserMessage($message['content']);
                } else {
                    $messages[] = new \NeuronAI\Chat\Messages\AssistantMessage($message['content']);
                }
            }

            // Add the current user message
            $messages[] = new UserMessage($userMessage);

            // Get response from Neuron AI
            $response = $agent->chat($messages);
            $content = $response->getContent();

            // Check if the response is a tool call (JSON array)
            if (substr($content, 0, 1) === '[' && substr($content, -1) === ']') {
                try {
                    $toolCalls = json_decode($content, true);

                    // If it's a valid JSON array and contains tool calls
                    if (is_array($toolCalls) && !empty($toolCalls)) {
                        Log::info('Tool call detected', ['tool_calls' => $toolCalls]);

                        // Process each tool call
                        $results = [];
                        foreach ($toolCalls as $toolCall) {
                            if (isset($toolCall['name']) && isset($toolCall['arguments'])) {
                                $toolName = $toolCall['name'];
                                $arguments = $toolCall['arguments'];

                                Log::info("Executing tool: {$toolName}", ['arguments' => $arguments]);

                                // Execute the appropriate tool based on the name
                                switch ($toolName) {
                                    case 'search_events':
                                        $tool = new \App\Tools\EventSearchTool();
                                        $query = $arguments['query'] ?? '';
                                        $category = $arguments['category'] ?? null;
                                        $date = $arguments['date'] ?? null;
                                        $results[] = $tool($query, $category, $date);
                                        break;

                                    case 'get_event_details':
                                        $tool = new \App\Tools\EventDetailsTool();
                                        $eventId = $arguments['event_id'] ?? '';
                                        $results[] = $tool($eventId);
                                        break;

                                    case 'get_booking_info':
                                        $tool = new \App\Tools\BookingInfoTool();
                                        $reference = $arguments['reference'] ?? null;
                                        $results[] = $tool($reference);
                                        break;

                                    default:
                                        $results[] = "Unknown tool: {$toolName}";
                                        break;
                                }
                            }
                        }

                        // If we have results from tools, use them
                        if (!empty($results)) {
                            // Join the results with a separator
                            $toolResults = implode("\n\n", $results);

                            // Get a final response from the AI using the tool results
                            $finalPrompt = "I used the tools you requested and found the following information:\n\n{$toolResults}\n\nPlease provide a helpful response based on this information.";
                            $finalResponse = $agent->chat(new UserMessage($finalPrompt));
                            return $finalResponse->getContent();
                        }
                    }
                } catch (\Exception $jsonError) {
                    Log::error('Error processing tool call JSON', [
                        'error' => $jsonError->getMessage(),
                        'content' => $content
                    ]);
                }
            }

            // If not a tool call or tool execution failed, return the original content
            return $content;
        } catch (\Exception $e) {
            // Log the error with more details
            Log::error('Neuron AI error: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

            // Try a simpler approach as fallback
            try {
                $agent = app(EventureAssistant::class);
                $response = $agent->chat(new UserMessage($userMessage));
                return $response->getContent();
            } catch (\Exception $e2) {
                Log::error('Fallback error: ' . $e2->getMessage());
                Log::error('Fallback trace: ' . $e2->getTraceAsString());
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
