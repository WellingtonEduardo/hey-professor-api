<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, Response};

class LogoutController extends Controller
{
    public function __invoke(Request $request): Response
    {

        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return response()->noContent();
    }
}
