<?php

use App\Models\Organisation;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getCurrentOrganisation')) {
    /**
     * Get the current organisation based on the organisation ID in the route.
     * If no organisation ID is provided, return the first organisation of the user.
     *
     * @return \App\Models\Organisation|null
     */
    function getCurrentOrganisation()
    {
        $organisationId = request()->route('organisationId');
        
        if ($organisationId) {
            // Get the organisation by ID, but only if it belongs to the current user
            return Auth::user()->organisations()->find($organisationId);
        }
        
        // Fallback to the first organisation of the user
        return Auth::user()->organisations()->first();
    }
}
