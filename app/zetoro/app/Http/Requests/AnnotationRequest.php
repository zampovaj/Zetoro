<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AnnotationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:article,id',
            'x_min'      => 'required|numeric',
            'y_min'      => 'required|numeric',
            'x_max'      => 'required|numeric',
            'y_max'      => 'required|numeric',

            'note'            => 'required_without:highlight_color|nullable|string',
            'highlight_color' => 'required_without:note|nullable|string',
        ];
    }
}
