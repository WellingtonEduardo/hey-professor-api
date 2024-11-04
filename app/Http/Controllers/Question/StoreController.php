<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreQuestionRequest $request): string
    {
        $question = Question::create([
            'question' => $request->question,
            'user_id'  => auth()->user()->id,
        ]);

        return response()->json($question);
    }
}
