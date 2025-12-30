<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['nullable', 'string', 'min:3', 'max:50'],
            'email'    => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->id)],
            'password' => ['nullable', 'min:8', 'max:16'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'nome',
            'email'    => 'e-mail',
            'password' => 'senha',
        ];
    }
}
