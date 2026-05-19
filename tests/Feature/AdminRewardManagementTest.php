<?php

use App\Models\Reward;
use App\Models\User;
use Spatie\Permission\Models\Role;

function createRewardAdminUser(): User
{
    Role::findOrCreate('admin');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

test('admin can view the rewards tab', function () {
    $admin = createRewardAdminUser();

    Reward::create([
        'name' => 'First Steps',
        'description' => 'Reached 25 points.',
        'points_required' => 25,
    ]);

    $response = $this
        ->actingAs($admin)
        ->get(route('dashboard.admin', ['tab' => 'rewards']));

    $response
        ->assertOk()
        ->assertSee('Rewards')
        ->assertSee('First Steps')
        ->assertSee('25');
});

test('admin can create a reward', function () {
    $admin = createRewardAdminUser();

    $response = $this
        ->actingAs($admin)
        ->post(route('dashboard.admin.rewards.store'), [
            'name' => 'Insight Champion',
            'description' => 'Reached 500 points.',
            'points_required' => 500,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Reward created successfully.')
        ->assertRedirect(route('dashboard.admin', ['tab' => 'rewards']));

    $this->assertDatabaseHas('rewards', [
        'name' => 'Insight Champion',
        'points_required' => 500,
    ]);
});

test('admin can update a reward', function () {
    $admin = createRewardAdminUser();
    $reward = Reward::create([
        'name' => 'Contributor',
        'description' => 'Reached 100 points.',
        'points_required' => 100,
    ]);

    $response = $this
        ->actingAs($admin)
        ->patch(route('dashboard.admin.rewards.update', $reward), [
            'name' => 'Core Contributor',
            'description' => 'Reached 150 points.',
            'points_required' => 150,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Reward updated successfully.')
        ->assertRedirect(route('dashboard.admin', ['tab' => 'rewards']));

    $this->assertDatabaseHas('rewards', [
        'reward_id' => $reward->reward_id,
        'name' => 'Core Contributor',
        'points_required' => 150,
    ]);
});

test('admin can delete a reward', function () {
    $admin = createRewardAdminUser();
    $reward = Reward::create([
        'name' => 'Feedback Guru',
        'description' => 'Reached 250 points.',
        'points_required' => 250,
    ]);

    $response = $this
        ->actingAs($admin)
        ->delete(route('dashboard.admin.rewards.destroy', $reward));

    $response
        ->assertSessionHas('success', 'Reward deleted successfully.')
        ->assertRedirect(route('dashboard.admin', ['tab' => 'rewards']));

    $this->assertDatabaseMissing('rewards', [
        'reward_id' => $reward->reward_id,
    ]);
});
