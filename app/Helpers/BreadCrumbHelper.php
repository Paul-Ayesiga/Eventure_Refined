<?php

if (!function_exists('getBreadcrumbs')) {
    /**
     * Generate breadcrumbs based on the current route name.
     *
     * @return array
     */
    function getBreadcrumbs()
    {
        $breadcrumbs = [];
        $request = request();
        $organisationId = $request->route('organisationId');

        if (auth()->user()->hasRole(['organiser'])) {
            // If we have an organisation ID in the route, use it for the dashboard URL
            if ($organisationId) {
                $url = route('organisation-dashboard', ['organisationId' => $organisationId]);
            } else {
                // If no organisation ID in route, try to get the first organisation of the user
                $organisation = auth()->user()->organisations()->first();
                if ($organisation) {
                    $url = route('organisation-dashboard', ['organisationId' => $organisation->id]);
                } else {
                    $url = route('create-organisation');
                }
            }
        } elseif (auth()->user()->hasRole('admin')) {
            $url = url('/admin/dashboard');
        } else {
            $url = url('/usr/dashboard');
        }

        // Always start with Home
        $breadcrumbs[] = [
            'title' => 'Home',
            'url'   => $url,
        ];

        // Get the current route name (e.g., "events", "reports", etc.)
        $routeName = \Illuminate\Support\Facades\Route::currentRouteName();

        if ($routeName) {
            // Convert route name (e.g., "my-team") to a more friendly title ("My Team")
            $title = ucwords(str_replace('-', ' ', $routeName));

            // Use the URL helper to generate the URL for this route
            // This assumes that your route URL corresponds to the route name.
            // If not, you may need to maintain a mapping array.
            try {
                // For routes that require parameters like organisationId
                if ($routeName === 'organisation-dashboard' && $organisationId) {
                    $url = route($routeName, ['organisationId' => $organisationId]);
                } else {
                    $url = route($routeName);
                }

                $breadcrumbs[] = [
                    'title' => $title,
                    'url'   => $url,
                ];
            } catch (\Exception $e) {
                // If route generation fails, just use the title without a URL
                $breadcrumbs[] = [
                    'title' => $title,
                    'url'   => '#',
                ];
            }
        }

        return $breadcrumbs;
    }
}
