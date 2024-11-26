<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\{Response};

class VoteController extends Controller
{
    public function __invoke(Question $question, string $vote): Response
    {
        $question
        ->votes()
        ->create([
            $vote     => 1,
            'user_id' => user()->id,
        ]);

        return response()->noContent();
    }
}
