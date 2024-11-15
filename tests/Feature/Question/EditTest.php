<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $uniqueQuestion = uniqid() . 'update ipsum ipsum? ';

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

});
