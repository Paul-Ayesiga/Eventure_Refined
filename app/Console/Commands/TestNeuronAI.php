<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Agents\EventureAssistant;
use NeuronAI\Chat\Messages\UserMessage;
use Illuminate\Support\Facades\Log;

class TestNeuronAI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:neuron-ai {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Neuron AI agent with a message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Neuron AI Agent...');
        
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
            $this->line($response->getContent());
            
            // Check if any tools were used
            $this->newLine();
            $this->info('Debug Information:');
            $this->line('Response type: ' . get_class($response));
            
            // Log the response for debugging
            Log::info('Neuron AI Test Response', [
                'message' => $message,
                'response' => $response->getContent(),
                'response_type' => get_class($response)
            ]);
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            
            Log::error('Neuron AI Test Error', [
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
