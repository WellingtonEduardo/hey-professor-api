<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreQuestionRequest $request): QuestionResource
    {
        $question = Question::create([
            'question' => $request->question,
            'status'   => 'draft',
            'user_id'  => auth()->user()->id,
        ]);

        return QuestionResource::make($question);
    }
}
