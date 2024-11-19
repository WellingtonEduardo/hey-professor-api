<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertAuthenticatedAs, assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

it('should be able to register in the application', function () {

    postJson(route('register'), [
        'name'               => 'John Doe',
        'email'              => 'john.doe@example.com',
        'email_confirmation' => 'john.doe@example.com',
        'password'           => 'password',
    ])->assertOk();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'john.doe@example.com',
    ]);

    $joeDoe = User::whereEmail('john.doe@example.com')->first();

    assertTrue(Hash::check('password', $joeDoe->password));
});

it('should log the new user in the system', function () {

    postJson(route('register'), [
        'name'               => 'John Doe',
        'email'              => 'john.doe@example.com',
        'email_confirmation' => 'john.doe@example.com',
        'password'           => 'password',
    ])->assertOk();

    $user = User::first();

    assertAuthenticatedAs($user);
});

describe('validations', function () {

    test('name', function ($rule, $value) {

        postJson(route('register'), [
            'name' => $value,

        ])->assertJsonValidationErrors([
            'name' => $rule,
        ]);

    })->with([
        'required' => ['required', ''],
        'min:3'    => [' at least 3 characters', 'Ab'],
        'max:255'  => ['greater than 255 characters.', str_repeat('*', 256)],
    ]);

    test('email', function ($rule, $value) {
        if ($rule == 'email has already been taken') {
            User::factory()->create(['email' => $value]);
        }

        postJson(route('register'), [
            'email'    => $value,
            'password' => 'password',
            'name'     => 'Teste',

        ])->assertJsonValidationErrors([
            'email' => $rule,
        ]);

    })->with([
        'required'  => ['required', ''],
        'min:3'     => ['at least 3 characters', 'Ab'],
        'max:255'   => ['greater than 255 characters', str_repeat('*', 256)],
        'email'     => ['The email field must be a valid email address', 'not-email'],
        'unique'    => ['email has already been taken', 'teste@gmail.com'],
        'confirmed' => ['email field confirmation does not match', 'teste@gmail.com'],
    ]);

    test('password', function ($rule, $value) {

        postJson(route('register'), [
            'password' => $value,

        ])->assertJsonValidationErrors([
            'password' => $rule,
        ]);

    })->with([
        'required' => ['required', ''],
        'min:8'    => ['at least 8 characters', 'Ab'],
        'max:40'   => ['greater than 40 characters', str_repeat('*', 41)],

    ]);
});
