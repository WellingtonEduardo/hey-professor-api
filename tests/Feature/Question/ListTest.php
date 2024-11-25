<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('should be able to list only published questions', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $published = Question::factory()->published()->create();
    $draft     = Question::factory()->draft()->create();

    $response = getJson(route('questions.index'))
        ->assertOk();

    $response->assertJsonFragment([
        'id'         => $published->id,
        'question'   => $published->question,
        'status'     => $published->status,
        'created_by' => [
            'id'   => $published->user->id,
            'name' => $published->user->name,
        ],
        'created_at' => $published->created_at->format('Y-m-d'),
        'updated_at' => $published->updated_at->format('Y-m-d'),

        // TODO: add like and unlike count
    ])->assertJsonMissing([
        'question' => $draft->question,
        'status'   => $draft->status,
    ]);
});

it('should be able to search for a question', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    Question::factory()->published()
       ->create(['question' => 'First Question?']);
    Question::factory()->published()
    ->create(['question' => 'Second Question?']);

    getJson(route('questions.index', ['q' => 'first']))->assertOk()
        ->assertJsonMissing(['question' => 'Second Question?'])
        ->assertJsonFragment(['question' => 'First Question?']);

    getJson(route('questions.index', ['q' => 'second']))->assertOk()
    ->assertJsonMissing(['question' => 'First Question?'])
    ->assertJsonFragment(['question' => 'Second Question?']);
});
