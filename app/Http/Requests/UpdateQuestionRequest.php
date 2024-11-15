<?php

namespace App\Http\Requests;

use App\Models\Question;
use App\Rules\{OnlyAsDraft, WithQuestionMark};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('question'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Question $question */
        $question = $this->route('question');

        return [
            'question' => [
                'required',
                new WithQuestionMark(),
                new OnlyAsDraft($question),
                'min:10',
                Rule::unique('questions')->ignoreModel($question),
            ],
        ];
    }

}
