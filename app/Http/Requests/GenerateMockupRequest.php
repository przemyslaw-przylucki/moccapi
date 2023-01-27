<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateMockupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'replacements' => ['required', 'size:' . $this->route('mockup')->layers()->count()],
            'format' => ['required', 'in:png,webp,jpg'],
            'zoom' => ['nullable', 'int', 'between:0,200'],
            'quality' => ['nullable', 'int', 'between:0,1,2']
        ];
    }
}
