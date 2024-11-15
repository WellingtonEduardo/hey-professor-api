<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, putJson};

it('should be able to restore a question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $question = Question::factory()->create(['user_id' => $user->id]);
    $question->delete();
    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);

    putJson(route('questions.restore', $question))->assertNoContent();

    assertNotSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});
