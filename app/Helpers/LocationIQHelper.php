<?php

namespace App\Helpers;

class LocationIQHelper
{
    /**
     * Get the LocationIQ API key from the configuration
     *
     * @return string
     */
    public static function getApiKey()
    {
        return config('services.locationiq.api_key');
    }

    /**
     * Get the LocationIQ API key for use in JavaScript
     * This method can be used to safely expose the API key to frontend code
     *
     * @return string
     */
    public static function getJsApiKey()
    {
        return config('services.locationiq.api_key');
    }
}
