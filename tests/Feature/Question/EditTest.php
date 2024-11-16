<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $uniqueQuestion = 'update ipsum ipsum?';

    putJson(route('questions.update', $question), [
        'question' => $uniqueQuestion,
    ])->assertOk();

    // Verifica se a pergunta foi criada no banco de dados
    assertDatabaseHas('questions', [
        'id'       => $question->id,
        'user_id'  => $user->id,
        'question' => $uniqueQuestion,
    ]);
});

describe('Validations rules', function () {

    test('Question::required', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question::ending with question mark', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem ipsum mak test',
        ])->assertJsonValidationErrors([
            'question' => 'The question should end with question mark (?)',
        ]);
    });

    test('question::min characters should be 10', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Question?',
        ])->assertJsonValidationErrors([
            'question' => 'least 10 characters.',
        ]);
    });

    test('question::should be unique', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);

        Question::factory()->create([
            'question' => 'Lorem test1 update mak lorem?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem test1 update mak lorem?',
        ])->assertJsonValidationErrors([
            'question' => 'The question has already been taken.',
        ]);
    });

    test('question::should be unique only if id is different', function () {
        $user           = User::factory()->create();
        $uniqueQuestion = 'Lorem update mak lorem?';
        $question       = Question::factory()->create([
            'user_id'  => $user->id,
            'question' => $uniqueQuestion,

        ]);
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => $uniqueQuestion,
        ])->assertOk();
    });

    test('question:: should be able to edit only if the status is in draft', function () {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id, 'status' => 'published']);
        Sanctum::actingAs($user);

        putJson(route('questions.update', $question), [
            'question' => 'Lorem update mak lorem test?',
        ])->assertJsonValidationErrors([
            'question' => 'The question should be a draft to be able to edit.',
        ]);

        assertDatabaseHas('questions', [
            'id'       => $question->id,
            'question' => $question->question,
        ]);
    });

});

describe('security', function () {

    test(
        'only the person who create the question can update the same question',
        function () {
            $user1 = User::factory()->create();
            $user2 = User::factory()->create();

            $question = Question::factory()->create(['user_id' => $user1->id]);

            Sanctum::actingAs($user2);

            putJson(route('questions.update', $question), [
                'question' => 'update the question?',
            ])->assertForbidden();

            assertDatabaseHas('questions', [
                'id'       => $question->id,
                'question' => $question->question,
            ]);

        }
    );

});

test('after creating we should return a status 200 with the update question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $question = Question::factory()->create(['user_id' => $user->id]);

    $uniqueQuestion = 'Lorem ipsum ipsum?';

    $response = putJson(route('questions.update', $question), [
        'question' => $uniqueQuestion,
    ])->assertOk();

    $questionId = $response->json('data.id');
    $question   = Question::findOrFail($questionId);

    $response->assertJson([
        'data' => [
            'id'         => $question->id,
            'question'   => $question->question,
            'status'     => $question->status,
            'created_by' => [
                'id'   => $user->id,
                'name' => $user->name,
            ],
            'created_at' => $question->created_at->format('Y-m-d'),
            'updated_at' => $question->updated_at->format('Y-m-d'),
        ],
    ]);
});
