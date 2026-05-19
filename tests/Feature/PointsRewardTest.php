<?php

use App\Models\AnswerOptions;
use App\Models\Questions;
use App\Models\Reward;
use App\Models\Survey;
use App\Models\User;

function createSurveyWithQuestions(int $questionCount): array
{
    $owner = User::factory()->create();

    $survey = Survey::create([
        'title' => 'Customer Feedback',
        'description' => 'Tell us what you think.',
        'user_id' => $owner->user_id,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    $answers = [];

    for ($index = 1; $index <= $questionCount; $index++) {
        $question = Questions::create([
            'survey_id' => $survey->survey_id,
            'question_text' => 'Question '.$index,
        ]);

        $option = AnswerOptions::create([
            'question_id' => $question->question_id,
            'option_text' => 'Yes',
        ]);

        AnswerOptions::create([
            'question_id' => $question->question_id,
            'option_text' => 'No',
        ]);

        $answers[$question->question_id] = $option->option_id;
    }

    return [$survey, $answers];
}

test('submitting a survey awards five points per question', function () {
    $user = User::factory()->create(['points' => 10]);
    [$survey, $answers] = createSurveyWithQuestions(3);

    $response = $this
        ->actingAs($user)
        ->post(route('dashboard.form.vote', $survey), [
            'questions' => $answers,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard.explore'));

    expect($user->refresh()->points)->toBe(25);
});

test('duplicate submissions do not award points again', function () {
    $user = User::factory()->create();
    [$survey, $answers] = createSurveyWithQuestions(2);

    $this
        ->actingAs($user)
        ->post(route('dashboard.form.vote', $survey), [
            'questions' => $answers,
        ]);

    $this
        ->actingAs($user)
        ->from(route('dashboard.form-detail', $survey))
        ->post(route('dashboard.form.vote', $survey), [
            'questions' => $answers,
        ])
        ->assertSessionHas('error', 'You have already voted on this survey.');

    expect($user->refresh()->points)->toBe(10);
});

test('profile displays rewards earned from user points', function () {
    $user = User::factory()->create(['points' => 100]);

    Reward::create([
        'name' => 'Starter Reward',
        'description' => 'Reached 25 points.',
        'points_required' => 25,
    ]);

    Reward::create([
        'name' => 'Locked Reward',
        'description' => 'Reached 250 points.',
        'points_required' => 250,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('profile.edit'));

    $response
        ->assertOk()
        ->assertSee('100 PTS')
        ->assertSee('Starter Reward')
        ->assertDontSee('Locked Reward');
});
