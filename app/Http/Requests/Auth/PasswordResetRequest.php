<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PasswordResetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'email'    => ['required','email'],
            'code'     => ['required','digits:6'],
            'password' => ['required','string' ,'min:8', 'max:16'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Campo e-mail é obrigatório!',
            'email.email' => 'Necessário enviar e-mail válido!',
            'code.required' => 'Campo código é obrigatório!',
            'code.digits' => 'Código deve conter 6 digitos !',
            'password.required' => 'Campo senha é obrigatório!',
            'password.min' => 'Senha com no mínimo :min caracteres!',
        ];
    }
}
