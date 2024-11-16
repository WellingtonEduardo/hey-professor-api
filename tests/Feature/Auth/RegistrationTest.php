<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\{assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertTrue;

it('should be able to register in the application', function () {
    postJson(route('register'), [
        'name'     => 'John Doe',
        'email'    => 'john.doe@example.com',
        'password' => 'password',
    ])->assertSessionHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'john.doe@example.com',
    ]);

    $joeDoe = User::whereEmail('john.doe@example.com')->first();

    assertTrue(Hash::check('password', $joeDoe->password));
});
