<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDemoController extends Controller
{
    /**
     * Return demo categories for integration testing.
     */
    public function categories(Request $request): JsonResponse
    {
        $expectedToken = config('services.demo_api.token');
        $token = $request->bearerToken();

        if (! $expectedToken || ! hash_equals($expectedToken, (string) $token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'product_categories' => [
                ['name' => 'Demo: Smart TVs'],
                ['name' => 'Demo: Soundbars'],
                ['name' => 'Demo: Gaming PCs'],
                ['name' => 'Demo: VR Headsets'],
                ['name' => 'Demo: Microphones'],
                ['name' => 'Demo: Drones'],
                ['name' => 'Demo: E-Scooters'],
                ['name' => 'Demo: Smart Lighting'],
                ['name' => 'Demo: 3D Printers'],
                ['name' => 'Demo: Home Servers'],
            ],
            'service_categories' => [
                ['name' => 'Demo: Tech Setup'],
                ['name' => 'Demo: Warranty Extension'],
                ['name' => 'Demo: Premium Support'],
                ['name' => 'Demo: Cloud Migration'],
                ['name' => 'Demo: Data Backup'],
                ['name' => 'Demo: Security Patching'],
                ['name' => 'Demo: Network Tuning'],
                ['name' => 'Demo: App Modernization'],
                ['name' => 'Demo: Managed Hosting'],
                ['name' => 'Demo: IT Outsourcing'],
            ]
        ]);
    }
}
