<?php

namespace App\Http\Requests\OAuth;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'code' => 'required|string',
            'redirect_uri' => 'required|url',
        ];
    }
}
