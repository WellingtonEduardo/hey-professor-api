<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\Question;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreQuestionRequest $request): Response
    {
        $question = Question::create([
            'question' => $request->question,
            'status'   => 'draft',
            'user_id'  => auth()->user()->id,
        ]);

        return response([
            'id'         => $question->id,
            'question'   => $question->question,
            'status'     => $question->status,
            'created_at' => $question->created_at->format('Y-m-d'),
            'updated_at' => $question->updated_at->format('Y-m-d'),
        ], Response::HTTP_CREATED);
    }
}
