<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeApplySort(Builder $query, ?string $sortParam = null): Builder
    {
        // Define "created_at.desc" como padrão caso nada seja enviado
        $sortParam = $sortParam ?: 'created_at.desc';

        // Separa coluna e direção (ex: "name.asc")
        [$sort, $direction] = explode('.', $sortParam) + [null, 'desc'];

        // Direções permitidas
        $allowedDirections = ['asc', 'desc'];

        // Garante que a direção seja válida, senão usa "desc"
        $direction = in_array(strtolower($direction), $allowedDirections)
            ? strtolower($direction)
            : 'desc';

        // Pega os campos fillable do model
        $fillable = $this->getFillable();

        // Se a coluna não for permitida, usa "created_at"
        if (! in_array($sort, $fillable) && $sort !== 'created_at' && $sort !== 'updated_at') {
            $sort = 'created_at';
        }

        // Aplica a ordenação na query
        return $query->orderBy($sort, $direction);
    }
}
