<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'title' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'due_at' => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'nama laporan',
            'department_id' => 'departemen',
            'area_id' => 'area',
            'due_at' => 'batas waktu',
        ];
    }
}
