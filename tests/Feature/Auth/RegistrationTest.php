<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('customer');
    Role::findOrCreate('guest');
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->firstOrFail();

    expect($user->hasRole('guest'))->toBeTrue()
        ->and($user->hasRole('customer'))->toBeFalse()
        ->and($user->hasVerifiedEmail())->toBeFalse();

    $response->assertRedirect(route('dashboard.home', absolute: false));
});
