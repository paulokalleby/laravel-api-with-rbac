<?php

return [

    /*
    |--------------------------------------------------------------------------
    | RBAC Resources (Entities)
    |--------------------------------------------------------------------------
    |
    | Here you define the "resources" of the application, meaning the modules
    | or entities that will be managed by the access control system.
    | The key represents the internal identifier used in routes (e.g. "students"),
    | and the value is the human-readable label that will be displayed
    | in the admin panel.
    |
    */

    'resources' => [
        'permissions' => 'Permissões',
        'roles'       => 'Papéis',
        'users'       => 'Usuários',
    ],

    /*
    |--------------------------------------------------------------------------
    | RBAC Actions (Permissions)
    |--------------------------------------------------------------------------
    |
    | Here you define the standard actions that can be linked to each resource.
    | The key represents the action "slug" used in routes (e.g. "index", "store"),
    | and the value is the friendly label that will be displayed in the panel
    | (e.g. "List", "Create").
    |
    | These actions are combined with resources to form full permissions,
    | such as "students.index" (List Students) or "users.update" (Edit Users).
    |
    */
    'actions' => [
        'index'   => 'Listar',
        'show'    => 'Detalhes',
        'store'   => 'Criar',
        'update'  => 'Editar',
        'destroy' => 'Excluir',
    ],
];
