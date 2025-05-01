<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tools\EventDetailsTool;
use Illuminate\Support\Facades\Log;

class TestEventDetailsTool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:event-details {event_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the EventDetailsTool directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing EventDetailsTool...');
        
        // Get the event name from the command argument or prompt for it
        $eventName = $this->argument('event_name');
        if (!$eventName) {
            $eventName = $this->ask('Enter an event name to search for');
        }
        
        $this->info("Searching for event: \"{$eventName}\"");
        
        try {
            // Create the tool
            $tool = new EventDetailsTool();
            
            // Call the tool directly
            $this->info('Getting event details...');
            $result = $tool($eventName);
            
            // Output the result
            $this->newLine();
            $this->info('Tool Result:');
            $this->line($result);
            
            // Log the result for debugging
            Log::info('EventDetailsTool Test Result', [
                'event_name' => $eventName,
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            
            Log::error('EventDetailsTool Test Error', [
                'event_name' => $eventName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
