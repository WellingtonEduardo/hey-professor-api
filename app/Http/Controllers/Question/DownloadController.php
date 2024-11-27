<?php

namespace App\Http\Controllers\Question;

use App\Exports\QuestionsExport;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function __invoke(): BinaryFileResponse
    {
        return (new QuestionsExport())->download('questions.xlsx');
    }
}
