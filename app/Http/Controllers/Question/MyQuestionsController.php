<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class MyQuestionsController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {

        $status = request()->status;

        Validator::validate(
            ['status' => $status],
            ['status' => ['required', 'in:draft,published,archived']]
        );

        $questions = Question::query()
        ->whereStatus($status)
        ->whereUserId(auth()->id())->get();

        return QuestionResource::collection($questions);
    }
}
