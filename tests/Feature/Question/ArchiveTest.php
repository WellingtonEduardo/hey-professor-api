<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertSoftDeleted,
    deleteJson};

it('should be able to arquive a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('questions.archive', $question))->assertNoContent();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});
