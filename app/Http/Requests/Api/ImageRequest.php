<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'type' => 'required|string|in:avatar,topic',
        ];

        if ($this->type == 'avatar') {
            $rules['image'] = 'required|mimes:jpeg,jpg,png,gif,bmp|dimensions:min_width=200,min_height=200';
        } else {
            $rules['image'] = 'required|mimes:jpeg,jpg,png,gif,bmp';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.dimensions' => '图片清晰度不够，宽高需要大于200px',
        ];
    }
}
