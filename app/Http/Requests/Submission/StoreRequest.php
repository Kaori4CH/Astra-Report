<?php

namespace App\Http\Requests\Submission;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'drive_link' => [
                'required',
                'url:http,https',
                'max:2048',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $host = strtolower((string) parse_url((string) $value, PHP_URL_HOST));

                    if (! in_array($host, ['drive.google.com', 'docs.google.com'], true)) {
                        $fail('Link harus berasal dari Google Drive (drive.google.com atau docs.google.com).');
                    }
                },
            ],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'drive_link' => 'link Google Drive',
            'note' => 'catatan',
        ];
    }
}
