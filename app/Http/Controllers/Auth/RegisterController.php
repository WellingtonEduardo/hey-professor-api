<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{Response};

class RegisterController extends Controller
{
    public function __invoke(): Response
    {
        $data = request()->validate([
            'name'     => ['required', 'min:3', 'max:255'],
            'email'    => ['required', 'min:3', 'max:255', 'email'],
            'password' => ['required', 'min:8', 'max:40'],

        ]);
        $user = User::create($data);
        auth()->login($user);

        return response(['message' => 'Registered with success.', 'user' => $user], 200);
    }
}