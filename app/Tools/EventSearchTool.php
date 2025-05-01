<?php

namespace App\Tools;

use App\Models\Event;
use Illuminate\Support\Carbon;

class EventSearchTool
{
    /**
     * Search for events based on query, category, and date
     *
     * @param string $query The search query
     * @param string|null $category The event category
     * @param string|null $date The date to search from (YYYY-MM-DD)
     * @return string
     */
    public function __invoke(string $query, ?string $category = null, ?string $date = null)
    {
        // Log the parameters for debugging
        \Illuminate\Support\Facades\Log::info('EventSearchTool called', [
            'query' => $query,
            'category' => $category,
            'date' => $date
        ]);
        $queryBuilder = Event::query()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereJsonContains('tags', $query);
            });

        // Log the SQL query for debugging
        \Illuminate\Support\Facades\Log::info('EventSearchTool SQL', [
            'sql' => $queryBuilder->toSql(),
            'bindings' => $queryBuilder->getBindings()
        ]);

        $queryBuilder = $queryBuilder->when($category, function ($q) use ($category) {
                return $q->where('category', 'like', "%{$category}%");
            })
            ->when($date, function ($q) use ($date) {
                try {
                    $searchDate = Carbon::parse($date);
                    return $q->where(function($query) use ($searchDate) {
                        $query->where(function($q) use ($searchDate) {
                            // For single day events
                            $q->where('start_date', '<=', $searchDate)
                              ->where(function($q) use ($searchDate) {
                                  $q->whereNull('end_date')
                                    ->orWhere('end_date', '>=', $searchDate);
                              });
                        });
                    });
                } catch (\Exception $e) {
                    // If date parsing fails, ignore the date filter
                    return $q;
                }
            })
            // Don't filter by status or archived status for testing
            // ->where('status', 'Published')
            // ->where('is_archived', false)
            ->orderBy('start_date')
            ->limit(5);

        // Log the final SQL query for debugging
        \Illuminate\Support\Facades\Log::info('EventSearchTool final SQL', [
            'sql' => $queryBuilder->toSql(),
            'bindings' => $queryBuilder->getBindings()
        ]);

        $events = $queryBuilder->get();

        if ($events->isEmpty()) {
            return "No events found matching your criteria.";
        }

        $result = "Here are some events I found:\n\n";
        foreach ($events as $event) {
            $dateInfo = $event->end_date
                ? "from " . $event->start_date->format('M j, Y') . " to " . $event->end_date->format('M j, Y')
                : "on " . $event->start_date->format('M j, Y');

            $location = $event->venue ?: ($event->location ? $event->location->display_name : 'Online');

            $result .= "- **{$event->name}** {$dateInfo}\n";
            $result .= "  Location: {$location}\n";
            if ($event->description) {
                $shortDesc = substr(strip_tags($event->description), 0, 100);
                $result .= "  Description: " . $shortDesc . (strlen($event->description) > 100 ? "..." : "") . "\n";
            }
            $result .= "\n";
        }

        return $result;
    }
}
