<?php

namespace App\Console\Commands;

use App\Http\Middleware\CheckLoggedUserPermissions;
use App\Models\Permission;
use App\Models\Resource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class SyncPermissionsCommand extends Command
{
    protected $signature = 'rbac:sync';

    protected $description = 'Sincroniza resources e permissions com base nas rotas nomeadas da API';

    public function handle()
    {
        $routes           = Route::getRoutes();
        $countResources   = 0;
        $countPermissions = 0;

        foreach ($routes as $route) {
            $routeName = $route->getName();

            if (! $routeName) {
                continue;
            }

            $middlewares = $route->gatherMiddleware();

            if (! in_array(CheckLoggedUserPermissions::class, $middlewares)) {
                continue;
            }

            $parts = explode('.', $routeName);
            if (count($parts) < 2) {
                continue;
            }

            $resourceName   = $parts[0];     // users
            $permissionName = end($parts);   // update
            $action         = $routeName;    // users.update

            $resource = Resource::firstOrCreate(
                ['name' => $resourceName]
            );
            if ($resource->wasRecentlyCreated) {
                $countResources++;
            }

            $permission = Permission::firstOrCreate(
                [
                    'resource_id' => $resource->id,
                    'action'      => $action,
                ],
                [
                    'name' => $permissionName,
                ]
            );

            if ($permission->wasRecentlyCreated) {
                $countPermissions++;
            }
        }

        $this->info("Sincronização concluída: {$countResources} resources novos, {$countPermissions} permissions novas.");
    }
}
