<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Response;

class ArchiveController extends Controller
{
    public function __invoke(Question $question): Response
    {
        $this->authorize('archive', $question);
        $question->delete();

        return response()->noContent();
    }
}
