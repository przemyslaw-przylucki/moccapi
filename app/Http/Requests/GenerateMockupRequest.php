<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateMockupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'replacement' => ['required'],
            'format' => ['required', 'in:png,webp,jpg']
        ];
    }
}
