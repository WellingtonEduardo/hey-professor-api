<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

it('should list only questions that the logged user has been created :: published', function () {
    $user                   = User::factory()->create();
    $userQuestion           = Question::factory()->published()->for($user)->create();
    $otherUserQuestion      = Question::factory()->published()->create();
    $userDraftQuestion      = Question::factory()->draft()->for($user)->create();
    $otherUserDraftQuestion = Question::factory()->draft()->create();

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
    ])->assertJsonMissing([
        'question' => $otherUserDraftQuestion->question,
    ])->assertJsonMissing([
        'question' => $userDraftQuestion->question,
    ]);
});

it('should list only questions that the logged user has been created :: draft', function () {
    $user                       = User::factory()->create();
    $userQuestion               = Question::factory()->draft()->for($user)->create();
    $otherUserQuestion          = Question::factory()->draft()->create();
    $userPublishedQuestion      = Question::factory()->published()->for($user)->create();
    $otherUserPublishedQuestion = Question::factory()->published()->create();

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
    ])->assertJsonMissing([
        'question' => $otherUserPublishedQuestion->question,
    ])->assertJsonMissing([
        'question' => $userPublishedQuestion->question,
    ]);
});

it('should list only questions that the logged user has been created :: archived', function () {
    $user              = User::factory()->create();
    $userQuestion      = Question::factory()->archived()->for($user)->create();
    $otherUserQuestion = Question::factory()->archived()->create();

    Sanctum::actingAs($user);

    $response = getJson(route('my-questions', ['status' => 'archived']))
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

test('making sure that only draft, published, and archived statuses can be passed to the route', function ($status, $code) {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    getJson(route('my-questions', ['status' => $status]))
    ->assertStatus($code);
})->with([
    ['draft', 200],
    ['published', 200],
    ['archived', 200],
    ['invalid', 422],

]);
