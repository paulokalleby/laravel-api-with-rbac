<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Relationable
{
    public function __call($method, $parameters)
    {
        // Captura chamadas tipo syncRoles(), attachRoles(), detachRoles()
        if (preg_match('/^(sync|attach|detach)([A-Z].*)$/', $method, $matches)) {

            $operation    = $matches[1];              // sync | attach | detach
            $relationName = Str::camel($matches[2]); // Roles -> roles

            if (!method_exists($this, $relationName)) {
                throw new \Exception("Relation '{$relationName}' does not exist on " . static::class);
            }

            $relation = $this->{$relationName}();

            $ids = $parameters[0] ?? [];

            return $this->handleRelationOperation($relation, $operation, $ids);
        }

        // Se não for método esperado, usa o comportamento normal
        return parent::__call($method, $parameters);
    }

    protected function handleRelationOperation($relation, string $operation, array $ids)
    {
        $ids = array_values($ids);

        return match ($operation) {
            'sync'   => $relation->sync($ids),
            'attach' => !empty($ids) ? $relation->attach($ids) : null,
            'detach' => $relation->detach($ids), // se vier vazio → remove tudo
            default  => throw new \Exception("Invalid operation '{$operation}'"),
        };
    }
}
