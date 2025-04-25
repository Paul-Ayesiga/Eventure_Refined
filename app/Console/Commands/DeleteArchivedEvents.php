<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeleteArchivedEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:delete-archived';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete events that have been archived for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to delete old archived events...');
        
        // Get all archived events that were archived more than 30 days ago
        $events = Event::where('is_archived', true)
            ->whereNotNull('archived_at')
            ->where('archived_at', '<', now()->subDays(30))
            ->get();
            
        $count = 0;
        
        foreach ($events as $event) {
            try {
                DB::beginTransaction();
                
                // Delete associated records
                // This assumes you have cascading deletes set up in your migrations
                // If not, you'll need to manually delete related records here
                
                // Log the event details before deletion for audit purposes
                Log::info("Deleting archived event: {$event->name} (ID: {$event->id}) archived at {$event->archived_at}");
                
                // Delete the event
                $event->delete();
                $count++;
                
                DB::commit();
                
                $this->info("Deleted event: {$event->name} (ID: {$event->id})");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to delete event {$event->id}: " . $e->getMessage());
                $this->error("Failed to delete event {$event->name} (ID: {$event->id}): {$e->getMessage()}");
            }
        }
        
        $this->info("Deleted {$count} archived events.");
        
        return Command::SUCCESS;
    }
}
