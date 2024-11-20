<?php

use App\Models\User;

use function Pest\Laravel\{actingAs, assertGuest, postJson};

it('should be able to logout', function () {
    $user = User::factory()->create();

    actingAs($user);

    postJson(route('logout'))->assertNoContent();

    assertGuest();
});
