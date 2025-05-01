<?php

namespace App\Tools;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class ListEventsTool
{
    /**
     * List available events
     *
     * @param string|null $filter Filter type: 'all', 'upcoming', 'past', 'today' (default: 'all')
     * @param string|null $category Category to filter by (optional)
     * @param int $limit Maximum number of events to return (default: 10)
     * @return string
     */
    public function __invoke(?string $filter = 'all', ?string $category = null, int $limit = 10)
    {
        try {
            Log::info("ListEventsTool called", [
                'filter' => $filter,
                'category' => $category,
                'limit' => $limit
            ]);
            
            // Start with a base query
            $query = Event::query();
            
            // Add status filter - only published events
            $query->where('status', 'Published');
            
            // Add archived filter - only non-archived events
            $query->where(function($q) {
                $q->where('is_archived', false)
                  ->orWhereNull('is_archived');
            });
            
            // Add date filter
            $now = Carbon::now();
            switch (strtolower($filter)) {
                case 'upcoming':
                    $query->where('start_date', '>=', $now->format('Y-m-d'));
                    break;
                case 'past':
                    $query->where('start_date', '<', $now->format('Y-m-d'));
                    break;
                case 'today':
                    $query->whereDate('start_date', $now->format('Y-m-d'));
                    break;
                case 'all':
                default:
                    // No additional filter
                    break;
            }
            
            // Add category filter if provided
            if ($category) {
                $query->where('category', 'like', "%{$category}%");
            }
            
            // Order by start date
            $query->orderBy('start_date', 'asc');
            
            // Limit the results
            $query->limit($limit);
            
            // Execute the query
            $events = $query->get();
            
            Log::info("ListEventsTool query executed", ['count' => $events->count()]);
            
            // Format the results
            return $this->formatEvents($events);
            
        } catch (\Exception $e) {
            Log::error("Error in ListEventsTool: " . $e->getMessage());
            return "Error listing events: " . $e->getMessage();
        }
    }
    
    /**
     * Format events for display
     *
     * @param \Illuminate\Database\Eloquent\Collection $events
     * @return string
     */
    private function formatEvents($events)
    {
        if ($events->isEmpty()) {
            return "I couldn't find any events matching your criteria.";
        }
        
        $result = "Here are the events I found:\n\n";
        
        foreach ($events as $index => $event) {
            $result .= "**" . ($index + 1) . ". " . $event->name . "**\n";
            
            // Format date
            if ($event->start_date) {
                $date = Carbon::parse($event->start_date)->format('F j, Y');
                $result .= "📅 " . $date . "\n";
            }
            
            // Format time
            if ($event->start_datetime && $event->end_datetime) {
                $startTime = Carbon::parse($event->start_datetime)->format('g:i A');
                $endTime = Carbon::parse($event->end_datetime)->format('g:i A');
                $result .= "⏰ " . $startTime . " - " . $endTime;
                
                if ($event->timezone) {
                    $result .= " (" . $event->timezone . ")";
                }
                
                $result .= "\n";
            }
            
            // Format venue/location
            if ($event->venue) {
                $result .= "📍 " . $event->venue . "\n";
            }
            
            // Format category
            if ($event->category) {
                $result .= "🏷️ " . $event->category . "\n";
            }
            
            // Format description (shortened)
            if ($event->description) {
                $description = strip_tags($event->description);
                if (strlen($description) > 100) {
                    $description = substr($description, 0, 97) . '...';
                }
                $result .= "📝 " . $description . "\n";
            }
            
            // Add a separator between events
            $result .= "\n";
        }
        
        return $result;
    }
}
