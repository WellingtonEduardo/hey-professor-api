<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to store a new question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $uniqueQuestion = 'Lorem ipsum ipsum?';
    // Executa a requisição e guarda a resposta
    $response = postJson(route('questions.store'), [
        'question' => $uniqueQuestion,
    ])->assertSuccessful();

    // Verifica se a pergunta foi criada no banco de dados
    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => $uniqueQuestion,
    ]);
});

test('with the creation of the question, we need to make sure that it crates with status  _draft', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $uniqueQuestion = 'Lorem ipsum ipsum?';

    postJson(route('questions.store'), [
        'question' => $uniqueQuestion,
    ])->assertSuccessful();

    // Verifica se a pergunta foi criada no banco de dados
    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'status'   => 'draft',
        'question' => $uniqueQuestion,
    ]);
});

describe('Validations rules', function () {

    test('Question::required', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        postJson(route('questions.store'), [])->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question::ending with question mark', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'Lorem ipsum mak test',
        ])->assertJsonValidationErrors([
            'question' => 'The question should end with question mark (?)',
        ]);
    });

    test('question::min characters should be 10', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'Question?',
        ])->assertJsonValidationErrors([
            'question' => 'least 10 characters.',
        ]);
    });

    test('question::should be unique', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Question::factory()->create([
            'question' => 'Lorem test mak lorem?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);

        postJson(route('questions.store'), [
            'question' => 'Lorem test mak lorem?',
        ])->assertJsonValidationErrors([
            'question' => 'The question has already been taken.',
        ]);
    });

});

test('after creating we should return a status 201 with the created question', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $uniqueQuestion = 'Lorem ipsum ipsum?';

    $response = postJson(route('questions.store'), [
        'question' => $uniqueQuestion,
    ])->assertCreated();

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
