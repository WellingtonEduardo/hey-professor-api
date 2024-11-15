<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, deleteJson};

it('should be able to destroy a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('questions.destroy', $question))->assertNoContent();

    assertDatabaseMissing('questions', [
        'id' => $question->id,
    ]);
});

describe('security', function () {

    test(
        'only the person who create the question can destroy the same question',
        function () {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();

            $question = Question::factory()->create(['user_id' => $user1->id]);

            Sanctum::actingAs($user2);

            deleteJson(route('questions.destroy', $question))->assertForbidden();

            assertDatabaseHas('questions', [
                'id' => $question->id,
            ]);

        }
    );

});
