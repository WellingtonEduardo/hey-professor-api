<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Response;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Question $question): Response
    {
        $this->authorize('forceDelete', $question);
        $question->forceDelete();

        return response()->noContent();
    }
}