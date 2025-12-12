<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->word;

        return [
            'name' => ucfirst($name),
        ];
    }
}
