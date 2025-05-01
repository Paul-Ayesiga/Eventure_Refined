<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Agents\EventureAssistant;
use NeuronAI\Chat\Messages\UserMessage;
use App\Tools\EventDetailsTool;
use App\Tools\EventSearchTool;
use App\Tools\DynamicQueryTool;
use Illuminate\Support\Facades\Log;

class TestNeuronAIToolExecution extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:neuron-tool-execution {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Neuron AI tool execution flow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Neuron AI Tool Execution Flow...');

        // Get the message from the command argument or prompt for it
        $message = $this->argument('message');
        if (!$message) {
            $message = $this->ask('Enter a message to send to the AI');
        }

        $this->info("Sending message: \"{$message}\"");

        try {
            // Create the agent
            $agent = new EventureAssistant();

            // Send the message
            $this->info('Waiting for response...');
            $response = $agent->chat(new UserMessage($message));

            // Output the response
            $this->newLine();
            $this->info('AI Response:');
            $content = $response->getContent();
            $this->line($content);

            // Check if the response is a tool call (JSON array)
            if (substr($content, 0, 1) === '[' && substr($content, -1) === ']') {
                try {
                    $toolCalls = json_decode($content, true);

                    // If it's a valid JSON array and contains tool calls
                    if (is_array($toolCalls) && !empty($toolCalls)) {
                        $this->info('Tool call detected:');
                        $this->line(json_encode($toolCalls, JSON_PRETTY_PRINT));

                        // Process each tool call
                        foreach ($toolCalls as $toolCall) {
                            if (isset($toolCall['name']) && isset($toolCall['arguments'])) {
                                $toolName = $toolCall['name'];
                                $arguments = $toolCall['arguments'];

                                $this->info("Executing tool: {$toolName}");
                                $this->line("Arguments: " . json_encode($arguments, JSON_PRETTY_PRINT));

                                // Execute the appropriate tool based on the name
                                switch ($toolName) {
                                    case 'get_event_details':
                                        $tool = new EventDetailsTool();
                                        $eventId = $arguments['event_id'] ?? '';
                                        $this->info("Calling EventDetailsTool with event_id: {$eventId}");
                                        $result = $tool($eventId);
                                        $this->line("Tool result:");
                                        $this->line($result);

                                        // Now send the result back to the AI
                                        $this->info("Sending tool result back to AI...");
                                        $finalPrompt = "I used the get_event_details tool and found the following information:\n\n{$result}\n\nPlease provide a helpful response based on this information about the event.";
                                        $finalResponse = $agent->chat(new UserMessage($finalPrompt));
                                        $this->info("Final AI response:");
                                        $this->line($finalResponse->getContent());
                                        break;

                                    case 'search_events':
                                        $tool = new EventSearchTool();
                                        $query = $arguments['query'] ?? '';
                                        $category = $arguments['category'] ?? null;
                                        $date = $arguments['date'] ?? null;
                                        $this->info("Calling EventSearchTool with query: {$query}");
                                        $result = $tool($query, $category, $date);
                                        $this->line("Tool result:");
                                        $this->line($result);

                                        // Now send the result back to the AI
                                        $this->info("Sending tool result back to AI...");
                                        $finalPrompt = "I used the search_events tool and found the following information:\n\n{$result}\n\nPlease provide a helpful response based on this search for events.";
                                        $finalResponse = $agent->chat(new UserMessage($finalPrompt));
                                        $this->info("Final AI response:");
                                        $this->line($finalResponse->getContent());
                                        break;

                                    case 'dynamic_query':
                                        $tool = new DynamicQueryTool();
                                        $question = $arguments['question'] ?? '';
                                        $this->info("Calling DynamicQueryTool with question: {$question}");
                                        $result = $tool($question);
                                        $this->line("Tool result:");
                                        $this->line($result);

                                        // Now send the result back to the AI
                                        $this->info("Sending tool result back to AI...");
                                        $finalPrompt = "I used the dynamic_query tool and found the following information:\n\n{$result}\n\nPlease provide a helpful response based on this information.";
                                        $finalResponse = $agent->chat(new UserMessage($finalPrompt));
                                        $this->info("Final AI response:");
                                        $this->line($finalResponse->getContent());
                                        break;

                                    default:
                                        $this->error("Unknown tool: {$toolName}");
                                        break;
                                }
                            }
                        }
                    }
                } catch (\Exception $jsonError) {
                    $this->error('Error processing tool call JSON: ' . $jsonError->getMessage());
                }
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());

            Log::error('Neuron AI Tool Execution Test Error', [
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
