<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class UserLocationService
{
    /**
     * Get the user's country code based on IP address.
     * Uses ip-api.com as requested.
     */
    public function getCountryCode(): ?string
    {
        // In local environment, return a default country code (e.g., 'TZ' for Tanzania)
        // or allow an override via .env
        if (app()->isLocal()) {
            return config('app.debug_country', 'TZ'); 
        }

        $ip = Request::ip();

        // Cache the result for 24 hours to minimize API calls
        return Cache::remember('user_country_' . $ip, 60 * 60 * 24, function () use ($ip) {
            try {
                // Determine user location from IP
                $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['status'] ?? 'fail') === 'success') {
                        return $data['countryCode'] ?? null;
                    }
                }
            } catch (\Exception $e) {
                // Fallback or log error
            }
            return null;
        });
    }
}
