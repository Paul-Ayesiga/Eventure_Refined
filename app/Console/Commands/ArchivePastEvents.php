<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ArchivePastEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:archive-past';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive events that have passed their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to archive past events...');

        // Get all non-archived events
        $events = Event::where('is_archived', false)->get();

        // Filter to only include events that have passed their end date by at least one day
        // This gives organizers time to wrap things up after the event ends
        $events = $events->filter(function ($event) {
            return $event->isPast(true); // true = include one-day buffer
        });

        $count = 0;

        foreach ($events as $event) {
            try {
                $event->archive();
                $count++;
                $this->info("Archived event: {$event->name} (ID: {$event->id})");
            } catch (\Exception $e) {
                Log::error("Failed to archive event {$event->id}: " . $e->getMessage());
                $this->error("Failed to archive event {$event->name} (ID: {$event->id}): {$e->getMessage()}");
            }
        }

        $this->info("Archived {$count} past events.");

        return Command::SUCCESS;
    }
}
