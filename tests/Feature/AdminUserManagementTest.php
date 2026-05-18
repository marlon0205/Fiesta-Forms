<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

function createAdminUser(): User
{
    Role::findOrCreate('admin');
    Role::findOrCreate('customer');
    Role::findOrCreate('guest');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('admin can open the user edit page from the users tab flow', function () {
    $admin = createAdminUser();
    $user = User::factory()->create();
    $user->assignRole('customer');

    $response = $this
        ->actingAs($admin)
        ->get(route('admin.user.edit', $user));

    $response
        ->assertOk()
        ->assertSee('Edit '.$user->name)
        ->assertSee('Save Changes')
        ->assertSee('Update Password')
        ->assertSee('Delete User');
});

test('admin can update a user profile from the admin edit page', function () {
    $admin = createAdminUser();
    $user = User::factory()->create();
    $user->assignRole('customer');

    $response = $this
        ->actingAs($admin)
        ->patch(route('admin.user.update', $user), [
            'name' => 'Updated Customer',
            'email' => 'updated@example.com',
            'is_active' => false,
            'role' => 'guest',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'User updated successfully.')
        ->assertRedirect(route('dashboard.admin', ['tab' => 'users']));

    $user->refresh();

    expect($user->name)->toBe('Updated Customer')
        ->and($user->email)->toBe('updated@example.com')
        ->and($user->is_active)->toBeFalse()
        ->and($user->hasRole('guest'))->toBeTrue()
        ->and($user->email_verified_at)->toBeNull();
});

test('admin can update another users password', function () {
    $admin = createAdminUser();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($admin)
        ->put(route('admin.user.password.update', $user), [
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'User password updated successfully.')
        ->assertRedirect(route('dashboard.admin', ['tab' => 'users']));

    expect(Hash::check('new-secure-password', $user->refresh()->password))->toBeTrue();
});
