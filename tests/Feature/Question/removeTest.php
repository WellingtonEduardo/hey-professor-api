<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseMissing, deleteJson};

it('should be able to destroy a question', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    deleteJson(route('questions.destroy', $question))->assertNoContent();

    // Verifica se a pergunta foi criada no banco de dados
    assertDatabaseMissing('questions', [
        'id' => $question->id,
    ]);
});
