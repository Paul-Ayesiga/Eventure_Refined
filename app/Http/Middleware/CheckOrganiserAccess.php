<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganiserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user->hasRole('organiser') || $user->organisations()->count() > 0) {
            return $next($request);
        }
        
        return redirect()->route('create-organisation')
            ->with('error', 'You need to create an organization first.');
    }
}
