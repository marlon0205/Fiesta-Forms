<?php

use App\Models\AnswerOptions;
use App\Models\Questions;
use App\Models\Survey;
use App\Models\User;
use App\Models\Votes;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('customer');
    Role::findOrCreate('guest');
});

test('unverified guest accounts are sent to email verification when voting', function () {
    $owner = User::factory()->create();
    $user = User::factory()->unverified()->create();
    $user->assignRole('guest');

    $survey = Survey::create([
        'title' => 'Customer feedback',
        'description' => 'Tell us what you think.',
        'duration_days' => 30,
        'is_active' => true,
        'user_id' => $owner->user_id,
    ]);
    $question = Questions::create([
        'survey_id' => $survey->survey_id,
        'question_text' => 'How was it?',
    ]);
    $option = AnswerOptions::create([
        'question_id' => $question->question_id,
        'option_text' => 'Great',
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('dashboard.form.vote', $survey), [
            'questions' => [
                $question->question_id => $option->option_id,
            ],
        ]);

    $response->assertRedirect(route('verification.notice'));
    expect(Votes::query()->count())->toBe(0);
});
