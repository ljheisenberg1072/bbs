<?php

namespace App\Http\Requests\Api;

class AuthorizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string',
            'password' => 'required|regex:/^[A-Za-z0-9\-\_\*@#]+$/|min:6',
        ];
    }
}
