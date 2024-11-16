<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{Request, Response};

class RegisterController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $data = request()->validate([
            'name'  => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'min:3', 'max:255', 'email'],

        ]);
        User::create($request->all());

        return response([], 200);
    }
}
