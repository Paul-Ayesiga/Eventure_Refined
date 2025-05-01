<?php

namespace App\Livewire\Assistant;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Agents\EventureAssistant;
use NeuronAI\Chat\Messages\UserMessage;
use App\Tools\EventDetailsTool;
use App\Tools\EventSearchTool;
use App\Tools\BookingInfoTool;
use App\Tools\DatabaseSchemaTool;
use App\Tools\DatabaseQueryTool;
use App\Tools\ModelInfoTool;
use App\Tools\DynamicQueryTool;

class CustomChatAssistant extends Component
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

        // Process the message and get a response
        $this->processMessage($userMessage);
    }

    protected function processMessage($userMessage)
    {
        try {
            // Let the AI decide which tool to use and get the response
            $response = $this->getAIResponse($userMessage);

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
            Log::error('Message processing error: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

            // Add a fallback response
            $this->isTyping = false;
            $this->messages[] = [
                'type' => 'assistant',
                'content' => "I'm sorry, I'm having trouble processing your request right now. Please try again in a moment.",
                'timestamp' => now()->timestamp,
                'id' => uniqid('msg_')
            ];

            // Dispatch event to scroll to bottom
            $this->dispatch('message-added');
        }
    }

    protected function getAIResponse($userMessage)
    {
        try {
            // Get the Neuron AI agent from the container
            $agent = app(EventureAssistant::class);

            // Log the user message
            Log::info('AI request', ['message' => $userMessage]);

            // Send the message to the AI
            $response = $agent->chat(new UserMessage($userMessage));
            $content = $response->getContent();

            // Log the AI response
            Log::info('AI response', ['content' => $content]);

            // Check if the response is a tool call (JSON array)
            if (substr($content, 0, 1) === '[' && substr($content, -1) === ']') {
                try {
                    $toolCalls = json_decode($content, true);

                    // If it's a valid JSON array and contains tool calls
                    if (is_array($toolCalls) && !empty($toolCalls)) {
                        Log::info('Tool call detected', ['tool_calls' => $toolCalls]);

                        // Process each tool call
                        foreach ($toolCalls as $toolCall) {
                            if (isset($toolCall['name']) && isset($toolCall['arguments'])) {
                                $toolName = $toolCall['name'];
                                $arguments = $toolCall['arguments'];

                                Log::info("Executing tool: {$toolName}", ['arguments' => $arguments]);

                                // Execute the appropriate tool based on the name
                                switch ($toolName) {
                                    case 'search_events':
                                        $tool = new EventSearchTool();
                                        $query = $arguments['query'] ?? '';
                                        $category = $arguments['category'] ?? null;
                                        $date = $arguments['date'] ?? null;
                                        $toolResult = $tool($query, $category, $date);
                                        break;

                                    case 'get_event_details':
                                        $tool = new EventDetailsTool();
                                        $eventId = $arguments['event_id'] ?? '';
                                        $toolResult = $tool($eventId);
                                        break;

                                    case 'get_booking_info':
                                        $tool = new BookingInfoTool();
                                        $reference = $arguments['reference'] ?? null;
                                        $toolResult = $tool($reference);
                                        break;

                                    case 'get_database_schema':
                                        $tool = new DatabaseSchemaTool();
                                        $tables = $arguments['tables'] ?? null;
                                        $toolResult = $tool($tables);
                                        break;

                                    case 'execute_query':
                                        $tool = new DatabaseQueryTool();
                                        $query = $arguments['query'] ?? '';
                                        $params = $arguments['params'] ?? [];
                                        $limit = $arguments['limit'] ?? 10;
                                        $toolResult = $tool($query, $params, $limit);
                                        break;

                                    case 'get_model_info':
                                        $tool = new ModelInfoTool();
                                        $models = $arguments['models'] ?? null;
                                        $toolResult = $tool($models);
                                        break;

                                    case 'dynamic_query':
                                        $tool = new DynamicQueryTool();
                                        $question = $arguments['question'] ?? '';
                                        Log::info("CustomChatAssistant - Calling DynamicQueryTool", ['question' => $question]);
                                        $toolResult = $tool($question);
                                        Log::info("CustomChatAssistant - DynamicQueryTool result", ['resultLength' => strlen($toolResult)]);
                                        break;

                                    default:
                                        $toolResult = "I'm not sure how to handle that request.";
                                        break;
                                }

                                // Now send the tool result back to the AI for a more natural, well-structured response
                                $promptInstructions = "Structure your response in a clear, engaging way with the following guidelines:
1. Start with a friendly greeting and introduction to the information
2. Present the key details in a logical, easy-to-read format
3. Highlight important information like dates, times, and prices
4. Add a personal touch - make recommendations or observations if appropriate
5. End with an offer to help with anything else
6. Keep your tone friendly and conversational throughout";

                                // Create tool-specific prompts
                                switch ($toolName) {
                                    case 'search_events':
                                        $finalPrompt = "Based on the user's search for events: \"{$userMessage}\", I found the following events:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response that presents these events in an engaging way. {$promptInstructions}
If there are multiple events, organize them clearly and highlight what makes each one special.";
                                        break;

                                    case 'get_event_details':
                                        $finalPrompt = "Based on the user's question about an event: \"{$userMessage}\", I found these event details:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response that presents this event information in an engaging way. {$promptInstructions}
Make sure to highlight the date, time, location, and ticket information prominently. If it's a special or unique event, emphasize what makes it stand out.";
                                        break;

                                    case 'get_booking_info':
                                        $finalPrompt = "Based on the user's request for booking information: \"{$userMessage}\", I found these booking details:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response that presents this booking information clearly. {$promptInstructions}
Make sure to highlight important details like event names, dates, and payment status. If there are multiple bookings, organize them in a clear way.";
                                        break;

                                    case 'get_database_schema':
                                        $finalPrompt = "Based on the user's request for database schema information: \"{$userMessage}\", I found the following schema details:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response that explains this database schema in plain language. {$promptInstructions}
Focus on explaining the purpose of the tables and their relationships in a way that's easy to understand. Don't just list all the columns unless specifically asked.";
                                        break;

                                    case 'execute_query':
                                        $finalPrompt = "Based on the user's database query: \"{$userMessage}\", I executed the query and found these results:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response that explains these query results in plain language. {$promptInstructions}
Interpret the data and explain what it means in the context of the user's question. If there are interesting patterns or insights in the data, point them out.";
                                        break;

                                    case 'get_model_info':
                                        $finalPrompt = "Based on the user's request for model information: \"{$userMessage}\", I found the following details about the Laravel models:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response that explains these models in plain language. {$promptInstructions}
Focus on explaining the purpose of the models and their relationships in a way that's easy to understand. Highlight important attributes and methods.";
                                        break;

                                    case 'dynamic_query':
                                        $finalPrompt = "Based on the user's question: \"{$userMessage}\", I found the following information:\n\n{$toolResult}\n\n
IMPORTANT: Only respond with information about events that actually exist in the database. If the result says 'I couldn't find any events matching your criteria', do NOT make up events - simply tell the user that no matching events were found.

Please provide a helpful, conversational response based on this information. {$promptInstructions}
Keep your response natural and engaging. The information is already formatted in a user-friendly way, so you can focus on adding context and being helpful.";
                                        break;

                                    default:
                                        $finalPrompt = "Based on the user's question: \"{$userMessage}\", I found the following information:\n\n{$toolResult}\n\n
Please provide a helpful, conversational response based on this information. {$promptInstructions}";
                                        break;
                                }
                                $finalResponse = $agent->chat(new UserMessage($finalPrompt));
                                return $finalResponse->getContent();
                            }
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
            // Log the error
            Log::error('AI response error: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());

            // Return a fallback response
            return "I'm sorry, I'm having trouble connecting to my brain right now. Please try again in a moment.";
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
