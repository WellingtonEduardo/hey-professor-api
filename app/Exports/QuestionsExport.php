<?php

namespace App\Exports;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\{Exportable, FromQuery};

class QuestionsExport implements FromQuery
{
    use Exportable;

    /**
     * @return Builder<Question>
     */
    public function query(): Builder
    {
        return Question::query();
    }
}
