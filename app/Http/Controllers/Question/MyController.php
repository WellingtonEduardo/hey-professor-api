<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class MyController extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {

        $status = request()->status;

        Validator::validate(
            ['status' => $status],
            ['status' => ['required', 'in:draft,published,archived']]
        );

        $questions = user()
        ->questions()
        ->when(
            $status === 'archived',
            fn (Builder $query) => $query->onlyTrashed(),
            fn (Builder $query) => $query->whereStatus($status)
        )
        ->get();

        return QuestionResource::collection($questions);
    }
}
