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

describe('security', function () {

    test(
        'only the person who create the question can restore the same question',
        function () {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();
            Sanctum::actingAs($user2);

            $question = Question::factory()->create(['user_id' => $user1->id]);
            $question->delete();
            assertSoftDeleted('questions', [
                'id' => $question->id,
            ]);

            putJson(route('questions.restore', $question))->assertForbidden();

            assertSoftDeleted('questions', [
                'id' => $question->id,
            ]);

        }
    );

});

it(
    'should only restore when the question is deleted',
    function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $question = Question::factory()->create(['user_id' => $user->id]);

        putJson(route('questions.restore', $question))->assertNotFound();

        assertNotSoftDeleted('questions', [
            'id' => $question->id,
        ]);

    }
);
