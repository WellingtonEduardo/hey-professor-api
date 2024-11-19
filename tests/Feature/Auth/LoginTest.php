<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertAuthenticatedAs, postJson};

it('should be able to login', function () {
    $user = User::factory()->create([
        'email'    => 'teste@gmail.com',
        'password' => Hash::make('123456789'),
    ]);

    postJson(route('login'), [
        'email'    => 'teste@gmail.com',
        'password' => '123456789',
    ])->assertNoContent();

    assertAuthenticatedAs($user);
});
