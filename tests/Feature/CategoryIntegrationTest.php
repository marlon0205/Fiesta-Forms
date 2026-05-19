<?php

use App\Models\Product_Categories;
use App\Models\Service_Categories;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class, WithoutMiddleware::class);

function createAdminForCategories(): User
{
    Role::findOrCreate('admin');
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    return $admin;
}

test('admin can sync categories via direct JSON', function () {
    $admin = createAdminForCategories();

    $jsonData = json_encode([
        'product_categories' => [
            ['name' => 'Tech Gadgets'],
            ['name' => 'Smart Home'],
        ],
        'service_categories' => [
            ['name' => 'Web Design'],
            ['name' => 'Cloud Hosting'],
        ]
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('dashboard.admin.integrations.sync'), [
            'json_data' => $jsonData,
        ]);

    $response
        ->assertRedirect(route('dashboard.admin', ['tab' => 'integrations']))
        ->assertSessionHas('success', 'Categories synced successfully.');

    expect(Product_Categories::where('name', 'Tech Gadgets')->exists())->toBeTrue()
        ->and(Product_Categories::where('name', 'Smart Home')->exists())->toBeTrue()
        ->and(Service_Categories::where('name', 'Web Design')->exists())->toBeTrue()
        ->and(Service_Categories::where('name', 'Cloud Hosting')->exists())->toBeTrue();
});

test('admin can sync categories via REST API', function () {
    $admin = createAdminForCategories();
    $apiUrl = 'https://api.example.com/categories';
    $apiToken = 'secret-token';

    Http::fake([
        $apiUrl => Http::response([
            'product_categories' => [
                ['name' => 'Office Supplies'],
            ],
            'service_categories' => [
                ['name' => 'Legal Consulting'],
            ]
        ], 200)
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('dashboard.admin.integrations.sync'), [
            'api_url' => $apiUrl,
            'api_token' => $apiToken,
        ]);

    Http::assertSent(function ($request) use ($apiUrl, $apiToken) {
        return $request->url() === $apiUrl &&
               $request->hasHeader('Authorization', 'Bearer ' . $apiToken);
    });

    $response
        ->assertRedirect(route('dashboard.admin', ['tab' => 'integrations']))
        ->assertSessionHas('success', 'Categories synced successfully.');

    expect(Product_Categories::where('name', 'Office Supplies')->exists())->toBeTrue()
        ->and(Service_Categories::where('name', 'Legal Consulting')->exists())->toBeTrue();
});

test('existing categories are not duplicated during sync', function () {
    $admin = createAdminForCategories();
    
    Product_Categories::create(['name' => 'Existing Product']);
    
    $jsonData = json_encode([
        'product_categories' => [
            ['name' => 'Existing Product'],
            ['name' => 'New Product'],
        ]
    ]);

    $this->actingAs($admin)
        ->post(route('dashboard.admin.integrations.sync'), [
            'json_data' => $jsonData,
        ]);

    expect(Product_Categories::where('name', 'Existing Product')->count())->toBe(1)
        ->and(Product_Categories::where('name', 'New Product')->count())->toBe(1);
});

test('invalid JSON data returns error', function () {
    $admin = createAdminForCategories();

    $response = $this
        ->actingAs($admin)
        ->post(route('dashboard.admin.integrations.sync'), [
            'json_data' => '{ invalid json }',
        ]);

    $response->assertSessionHas('error', 'Invalid JSON data provided.');
});

test('unreachable API URL returns friendly error', function () {
    $admin = createAdminForCategories();

    // Mock an exception (e.g., DNS failure)
    Http::fake(function ($request) {
        throw new \Exception('Connection refused');
    });

    $response = $this
        ->actingAs($admin)
        ->post(route('dashboard.admin.integrations.sync'), [
            'api_url' => 'https://broken-link.com',
        ]);

    $response->assertSessionHas('error');
    $errorMessage = session('error');
    expect($errorMessage)->toContain('Could not reach the API');
});
