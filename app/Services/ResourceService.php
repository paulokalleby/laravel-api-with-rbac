<?php

namespace App\Services;

use App\Models\Resource;
use Illuminate\Support\Collection;

class ResourceService
{
    public function __construct(protected Resource $resource) {}

    public function getResourceWithPermissions(): Collection
    {
        return $this->resource->with('permissions')->get();
    }
}
