<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to store a new question', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    // Executa a requisição e guarda a resposta
    $response = postJson(route('questions.store'), [
        'question' => 'Lorem ipsum Lorem ipsum?',
    ])->assertSuccessful();

    // Verifica se a pergunta foi criada no banco de dados
    assertDatabaseHas('questions', [
        'user_id'  => $user->id,
        'question' => 'Lorem ipsum Lorem ipsum?',
    ]);
});
