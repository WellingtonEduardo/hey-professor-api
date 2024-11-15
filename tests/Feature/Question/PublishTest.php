<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to publish a question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $question = Question::factory()->create(['user_id' => $user->id,  'status' => 'draft']);

    putJson(route('questions.publish', $question))->assertNoContent();

    assertDatabaseHas('questions', [
        'id'     => $question->id,
        'status' => 'published',
    ]);
});

describe('security', function () {

    test(
        'only the person who create the question can publish the same question',
        function () {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();

            Sanctum::actingAs($user2);
            $question = Question::factory()->create(['user_id' => $user1->id,  'status' => 'draft']);

            putJson(route('questions.publish', $question))->assertForbidden();

            assertDatabaseHas('questions', [
                'id'     => $question->id,
                'status' => 'draft',
            ]);

        }
    );

});

it(
    'should only publish when the question is on status draft',
    function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $question = Question::factory()->create(['user_id' => $user->id, 'status' => 'not-published']);

        putJson(route('questions.publish', $question))->assertNotFound();

        assertDatabaseHas('questions', [
            'id'     => $question->id,
            'status' => 'not-published',
        ]);

    }
);
