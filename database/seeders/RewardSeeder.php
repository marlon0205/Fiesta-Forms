<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            ['name' => 'First Steps', 'description' => 'Submitted answers worth 25 points.', 'points_required' => 25],
            ['name' => 'Contributor', 'description' => 'Submitted answers worth 100 points.', 'points_required' => 100],
            ['name' => 'Feedback Guru', 'description' => 'Submitted answers worth 250 points.', 'points_required' => 250],
            ['name' => 'Insight Champion', 'description' => 'Submitted answers worth 500 points.', 'points_required' => 500],
            ['name' => 'Master Contributor', 'description' => 'Submitted answers worth 1000 points.', 'points_required' => 1000],
        ];

        foreach ($rewards as $reward) {
            Reward::updateOrCreate(
                ['points_required' => $reward['points_required']],
                $reward,
            );
        }
    }
}
