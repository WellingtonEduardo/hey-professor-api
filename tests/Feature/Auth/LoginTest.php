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

it('should check if the email and password is invalid', function ($email, $password) {

    User::factory()->create([
        'email'    => 'teste@gmail.com',
        'password' => Hash::make('123456789'),
    ]);

    postJson(route('login'), [
        'email'    => $email,
        'password' => $password,
    ])->assertJsonValidationErrors([
        'email' => __('auth.failed'),
    ]);

})->with([
    'wrong email'    => ['wrong@email.com', '123456789'],
    'wrong password' => ['teste@gmail.com', 'password1234'],
    'invalid email'  => ['invalid-email', '123456789'],
]);
