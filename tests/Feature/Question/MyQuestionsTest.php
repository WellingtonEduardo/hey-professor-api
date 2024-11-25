<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('should list only questions that the logged user has been created :: published', function () {
    $user              = User::factory()->create();
    $userQuestion      = Question::factory()->published()->for($user)->create();
    $otherUserQuestion = Question::factory()->published()->create();

    Sanctum::actingAs($user);

    $response = getJson(route('my-questions', ['status' => 'published']))
    ->assertOk();

    $response->assertJsonFragment([
        'id'         => $userQuestion->id,
        'question'   => $userQuestion->question,
        'status'     => $userQuestion->status,
        'created_by' => [
            'id'   => $userQuestion->user->id,
            'name' => $userQuestion->user->name,
        ],
        'created_at' => $userQuestion->created_at->format('Y-m-d'),
        'updated_at' => $userQuestion->updated_at->format('Y-m-d'),

        // TODO: add like and unlike count
    ])->assertJsonMissing([
        'question' => $otherUserQuestion->question,
    ]);
});

it('should list only questions that the logged user has been created :: draft', function () {
    $user              = User::factory()->create();
    $userQuestion      = Question::factory()->draft()->for($user)->create();
    $otherUserQuestion = Question::factory()->draft()->create();

    Sanctum::actingAs($user);

    $response = getJson(route('my-questions', ['status' => 'draft']))
    ->assertOk();

    $response->assertJsonFragment([
        'id'         => $userQuestion->id,
        'question'   => $userQuestion->question,
        'status'     => $userQuestion->status,
        'created_by' => [
            'id'   => $userQuestion->user->id,
            'name' => $userQuestion->user->name,
        ],
        'created_at' => $userQuestion->created_at->format('Y-m-d'),
        'updated_at' => $userQuestion->updated_at->format('Y-m-d'),

        // TODO: add like and unlike count
    ])->assertJsonMissing([
        'question' => $otherUserQuestion->question,
    ]);
});
