<?php

namespace App\Http\Requests\OAuth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AuthorizeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('auth.oauth_clients')
                        ->where('client_id', $value)
                        ->whereNotNull('client_id')
                        ->exists();

                    if (! $exists) {
                        $fail("The selected $attribute is invalid.");
                    }
                },
            ],
            'redirect_uri' => 'required|url',
            'scope' => 'nullable|string',
            'state' => 'nullable|string',
        ];
    }
}
