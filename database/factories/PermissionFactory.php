<?php

namespace Database\Factories;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PermissionFactory extends Factory
{
    public function definition(): array
    {
        $name   = $this->faker->unique()->sentence(2);
        $action = Str::slug($name);

        return [
            'resource_id' => Resource::factory(),
            'name'        => ucfirst($name),
            'action'      => $action,
        ];
    }
}
