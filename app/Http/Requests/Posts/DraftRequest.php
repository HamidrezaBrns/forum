<?php

namespace App\Http\Requests\Posts;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DraftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required_without:body', 'min:5', 'max:120', 'string'],
            'body' => ['required_without:title', 'max:10000', 'string'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['string', 'exists:tags,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'tags.*.exists' => __('validation.custom.tags-exists'),
        ];
    }
}
