<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        $questions = Question::query()
        ->published()
        ->search(request()->q)
        ->withSum('votes', 'like')
        ->withSum('votes', 'unlike')
        ->get();

        return QuestionResource::collection($questions);
    }
}
