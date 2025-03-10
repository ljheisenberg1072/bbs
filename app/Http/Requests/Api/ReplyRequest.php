<?php

namespace App\Http\Requests\Api;

class ReplyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => 'required|min:2',
        ];
    }
}
