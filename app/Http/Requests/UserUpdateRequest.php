<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'max:16'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'is_active' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['uuid', 'distinct', 'exists:roles,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'password' => 'senha',
            'email' => 'e-mail',
            'is_active' => 'ativo',
            'roles' => 'papéis',
            'roles.*' => 'papel',
        ];
    }
}
