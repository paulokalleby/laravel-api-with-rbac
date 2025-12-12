<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required','email'],
            'code'  => ['required','digits:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'  => 'Campo código é obrigatório!',
            'code.digits' => 'Código deve conter 6 digitos !',
            'email.required' => 'Campo e-mail é obrigatório!',
            'email.email'    => 'Necessário enviar e-mail válido!',
        ];
    }
}
