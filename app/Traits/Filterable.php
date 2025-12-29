<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeApplyFilters(Builder $query, array $filters = []): Builder
    {
        if (empty($filters)) {
            return $query;
        }

        // Caso o model defina um array específico de campos filtráveis
        $filterable = property_exists($this, 'filterable')
            ? $this->filterable
            : array_fill_keys($this->getFillable(), '=');

        foreach ($filters as $field => $value) {

            // Ignora filtros vazios
            if (is_null($value) || $value === '') {
                continue;
            }

            // Ignora campos não permitidos
            if (! array_key_exists($field, $filterable)) {
                continue;
            }

            $operator = strtolower($filterable[$field]) ?? '=';

            // Operador LIKE
            if ($operator === 'like') {
                $query->where($field, 'LIKE', "%{$value}%");

                continue;
            }

            // Operador booleano direto
            if (in_array($operator, ['=', '>', '<', '>=', '<=', '!='])) {
                $query->where($field, $operator, $value);

                continue;
            }

            // Se o operador for uma função/scopo
            if (is_callable([$this, $operator])) {
                $this->{$operator}($query, $value);

                continue;
            }
        }

        return $query;
    }
}
