<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'min:2', 'max:50'],
            'is_active'     => ['nullable', 'boolean'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['uuid', 'distinct', 'exists:permissions,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'          => 'nome',
            'is_active'     => 'ativo',
            'permissions'   => 'permissões',
            'permissions.*' => 'permissão',
        ];
    }
}
