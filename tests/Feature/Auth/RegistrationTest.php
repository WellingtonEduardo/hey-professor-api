<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

it('should be able to register in the application', function () {

    $uniqueEmail = uniqid() . 'john.doe@example.com';

    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => $uniqueEmail,
        'password' => 'password',
    ])->assertOk();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => $uniqueEmail,
    ]);

    $joeDoe = User::whereEmail($uniqueEmail)->first();

    assertTrue(Hash::check('password', $joeDoe->password));
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

});
