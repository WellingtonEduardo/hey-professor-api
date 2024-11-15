<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\{Response};

class RestoreController extends Controller
{
    public function __invoke(int $id): Response
    {
        $question = Question::onlyTrashed()->findOrFail($id);
        $question->restore();

        return response()->noContent();
    }
}
