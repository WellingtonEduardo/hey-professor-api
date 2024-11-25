<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\{Request};

class IndexController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $questions = Question::whereStatus('published')->get();

        return QuestionResource::collection($questions);
    }
}
