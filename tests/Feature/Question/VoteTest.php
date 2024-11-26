<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to like a question', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $question = Question::factory()->published()->create();

    postJson(route('questions.vote', [
        'question' => $question,
        'vote'     => 'like',
    ]))->assertNoContent();

    expect($question->votes)
    ->toHaveCount(1);

    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'like'        => 1,
        'user_id'     => $user->id,
    ]);

});
