<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceWithPermissionsResource;
use App\Services\ResourceService;

class PermissionController extends Controller
{
    public function __construct(protected ResourceService $resource) {}

    public function index()
    {
        return ResourceWithPermissionsResource::collection(
            $this->resource->getResourceWithPermissions()
        );
    }
}
