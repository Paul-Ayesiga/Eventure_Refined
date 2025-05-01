<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tools\EventSearchTool;
use Illuminate\Support\Facades\Log;

class TestEventSearchTool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:event-search {query?} {--category=} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the EventSearchTool directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing EventSearchTool...');
        
        // Get the query from the command argument or prompt for it
        $query = $this->argument('query');
        if (!$query) {
            $query = $this->ask('Enter a search query');
        }
        
        // Get optional parameters
        $category = $this->option('category');
        $date = $this->option('date');
        
        $this->info("Searching for events with query: \"{$query}\"");
        if ($category) {
            $this->info("Category: \"{$category}\"");
        }
        if ($date) {
            $this->info("Date: \"{$date}\"");
        }
        
        try {
            // Create the tool
            $tool = new EventSearchTool();
            
            // Call the tool directly
            $this->info('Searching for events...');
            $result = $tool($query, $category, $date);
            
            // Output the result
            $this->newLine();
            $this->info('Tool Result:');
            $this->line($result);
            
            // Log the result for debugging
            Log::info('EventSearchTool Test Result', [
                'query' => $query,
                'category' => $category,
                'date' => $date,
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            
            Log::error('EventSearchTool Test Error', [
                'query' => $query,
                'category' => $category,
                'date' => $date,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
