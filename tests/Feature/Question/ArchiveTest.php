<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, deleteJson};

it('should be able to archive a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('questions.archive', $question))->assertNoContent();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});

describe('security', function () {

    test(
        'only the person who create the question can archive the same question',
        function () {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();

            $question = Question::factory()->create(['user_id' => $user1->id]);

            Sanctum::actingAs($user2);

            deleteJson(route('questions.archive', $question))->assertForbidden();

            assertNotSoftDeleted('questions', [
                'id' => $question->id,
            ]);

        }
    );

});
