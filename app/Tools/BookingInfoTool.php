<?php

namespace App\Tools;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingInfoTool
{
    /**
     * Get booking information for the current user or by reference
     *
     * @param string|null $reference The booking reference number (optional)
     * @return string
     */
    public function __invoke(?string $reference = null)
    {
        // Get the current authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return "You need to be logged in to check booking information. Please log in and try again.";
        }
        
        $query = Booking::query();
        
        // If a reference is provided, search by reference
        if ($reference) {
            $query->where('booking_reference', $reference);
            
            // If the user is not an admin or organizer, restrict to their own bookings
            if (!$user->hasRole(['admin', 'organiser'])) {
                $query->where('user_id', $user->id);
            }
        } else {
            // Otherwise, get the user's bookings
            $query->where('user_id', $user->id);
        }
        
        $bookings = $query->with(['event', 'bookingItems.ticket', 'attendees'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        if ($bookings->isEmpty()) {
            if ($reference) {
                return "No booking found with reference number: {$reference}";
            } else {
                return "You don't have any bookings yet.";
            }
        }
        
        $result = "Here are your booking details:\n\n";
        
        foreach ($bookings as $booking) {
            $result .= "**Booking Reference**: {$booking->booking_reference}\n";
            $result .= "**Event**: {$booking->event->name}\n";
            $result .= "**Date**: " . $booking->event->start_date->format('M j, Y') . "\n";
            $result .= "**Status**: {$booking->status}\n";
            $result .= "**Payment Status**: {$booking->payment_status}\n";
            $result .= "**Total Amount**: {$booking->total_amount} {$booking->event->currency}\n\n";
            
            $result .= "**Tickets**:\n";
            foreach ($booking->bookingItems as $item) {
                $result .= "- {$item->quantity}x {$item->ticket->name} @ {$item->price} {$booking->event->currency} each\n";
            }
            
            if ($booking->attendees->count() > 0) {
                $result .= "\n**Attendees**:\n";
                foreach ($booking->attendees as $attendee) {
                    $result .= "- {$attendee->name} ({$attendee->email})\n";
                }
            }
            
            $result .= "\n---\n\n";
        }
        
        return $result;
    }
}
