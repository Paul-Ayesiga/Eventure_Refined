<?php

namespace App\Tools;

use App\Models\Event;

class EventDetailsTool
{
    /**
     * Get detailed information about a specific event
     *
     * @param int|string $event_id The ID or name of the event
     * @return string
     */
    public function __invoke(string $event_id)
    {
        // Check if $event_id is numeric (ID) or a string (name)
        $query = Event::query()->with(['location', 'tickets', 'organisation']);

        if (is_numeric($event_id)) {
            $event = $query->find($event_id);
        } else {
            // Try to find by exact name first
            $event = $query->where('name', $event_id)->first();

            // If not found, try with partial name match
            if (!$event) {
                $event = $query->where('name', 'like', "%{$event_id}%")->first();
            }

            // If still not found, try with case-insensitive search
            if (!$event) {
                $event = Event::query()
                    ->with(['location', 'tickets', 'organisation'])
                    ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($event_id) . '%'])
                    ->first();
            }
        }

        if (!$event) {
            return "No event found with the provided information.";
        }

        // Don't show draft or archived events unless they're published
        if ($event->status !== 'Published' || $event->is_archived) {
            return "This event is not currently available.";
        }

        $result = "# {$event->name}\n\n";

        // Event type and dates
        $result .= "**Type**: {$event->event_type}\n";

        if ($event->end_date) {
            $result .= "**Dates**: {$event->start_date->format('M j, Y')} to {$event->end_date->format('M j, Y')}\n";
        } else {
            $result .= "**Date**: {$event->start_date->format('M j, Y')}\n";
        }

        $result .= "**Time**: {$event->start_datetime->format('g:i A')} to {$event->end_datetime->format('g:i A')} ({$event->timezone})\n";

        // Location
        if ($event->event_type === 'Venue Event') {
            $location = $event->venue;
            if ($event->location) {
                $location .= " - " . $event->location->display_name;
            }
            $result .= "**Location**: {$location}\n";
        } else {
            $result .= "**Location**: Online Event\n";
        }

        // Organizer
        if ($event->organisation) {
            $result .= "**Organized by**: {$event->organisation->name}\n";
        }

        // Category and tags
        if ($event->category) {
            $result .= "**Category**: {$event->category}\n";
        }

        if (!empty($event->tags)) {
            $tags = is_array($event->tags) ? implode(', ', $event->tags) : $event->tags;
            $result .= "**Tags**: {$tags}\n";
        }

        // Description
        if ($event->description) {
            $result .= "\n## Description\n\n" . strip_tags($event->description) . "\n";
        }

        // Tickets
        if ($event->tickets->count() > 0) {
            $result .= "\n## Tickets\n\n";

            foreach ($event->tickets as $ticket) {
                $availability = $ticket->quantity_available - $ticket->quantity_sold;
                $availabilityText = $availability > 0 ? "{$availability} available" : "Sold out";

                $result .= "- **{$ticket->name}**: {$ticket->price} {$event->currency} ({$availabilityText})\n";
                if ($ticket->description) {
                    $result .= "  {$ticket->description}\n";
                }
            }
        }

        return $result;
    }
}
