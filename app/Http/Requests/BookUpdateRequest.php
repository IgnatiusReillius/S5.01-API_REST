<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookUpdateRequest extends FormRequest
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
        $bookId = $this->route('id');

        return [
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => [
                'sometimes',
                'string',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'publisher' => 'sometimes|string|max:50',
            'publish_date' => 'sometimes|date',
            'pages' => 'sometimes|integer|gt:0',
            'summary' => 'sometimes|string|max:1000',
        ];
    }
}
