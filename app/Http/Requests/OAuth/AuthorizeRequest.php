<?php


namespace App\Http\Requests\OAuth;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id'    => 'required|string|exists:oauth_clients,client_id',
            'redirect_uri' => 'required|url',
            'scope'        => 'nullable|string',
            'state'        => 'nullable|string',
        ];
    }
}
