<?php

namespace App\Http\Requests\Review;

use App\Enums\SubmissionStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(array_map(fn (SubmissionStatus $status) => $status->value, SubmissionStatus::reviewOptions())),
            ],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'status pengumpulan',
            'note' => 'catatan',
        ];
    }
}
