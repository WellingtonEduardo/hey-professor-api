<?php

namespace App\Http\Requests;

use App\Rules\WithQuestionMark;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $question = $this->route('question');

        // Ensure $question is an object with an id property, or handle the case where it might not be
        if (is_object($question) && isset($question->id)) {
            $questionId = $question->id;
        } else {
            // Handle the case where the question is not found or it's not the expected object
            $questionId = null; // or throw an exception or handle as needed
        }

        return [
            'question' => ['required', new WithQuestionMark(), 'min:10',
                Rule::unique('questions')->ignore($questionId),
            ],
        ];
    }

}
