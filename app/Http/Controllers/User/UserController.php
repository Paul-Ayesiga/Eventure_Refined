<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function dashboard()
    {
        return view('user.dashboard');
    }

    /**
     * Display the user's bookings.
     */
    public function bookings()
    {
        $bookings = Booking::with(['event', 'attendees', 'tickets'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.bookings', compact('bookings'));
    }

    /**
     * Display the user's profile settings.
     */
    public function profileSettings()
    {
        return view('user.settings.profile');
    }

    /**
     * Display the user's password settings.
     */
    public function passwordSettings()
    {
        return view('user.settings.password');
    }

    /**
     * Display the user's appearance settings.
     */
    public function appearanceSettings()
    {
        return view('user.settings.appearance');
    }
}
