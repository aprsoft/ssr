<?php

namespace App\Helpers\Tenant;

use App\Helpers\Function\IconHelper;

class MenuHelper
{
    public static function getMainNavItems(): array
    {
        return [
            [
                'icon' => 'home',
                'name' => 'Inicio',
                'path' => route('tenant.dashboard', absolute: false),
            ],
            [
                'icon' => 'users',
                'name' => 'Clientes',
                'subItems' => [
                    [                        
                        'name' => 'Listado',
                        'path' => route('tenant.customers.index', absolute: false),
                        'pro'  => false
                    ],
                    [                        
                        'name' => 'Crear Cliente',
                        'path' => route('tenant.customers.create', absolute: false),
                        'pro'  => false
                    ],

                ],
            ], 
        ];
    }

    public static function getAdministrationItems(): array
    {
        return [
            [
                'icon' => 'users',
                'name' => 'Empleados',
                'subItems' => [
                    [                        
                        'name' => 'Listado',
                        'path' => route('tenant.employees.index', absolute: false),
                        'pro'  => false
                    ],
                    [                        
                        'name' => 'Crear Empleado',
                        'path' => route('tenant.employees.create', absolute: false),
                        'pro'  => false
                    ],

                ],
            ],           
        ];
    }

    public static function getRolesPermissionsItems(): array
    {
        return [
         
            [
                'icon' => 'access',
                'name' => 'Roles',
                'subItems' => [
                    [                        
                        'name' => 'listado',
                        'path' => route('tenant.roles.index', absolute: false),
                        'pro'  => false
                    ],
                    [                        
                        'name' => 'Crear Roles',
                        'path' => route('tenant.roles.create', absolute: false),
                        'pro'  => false
                    ],
                    
                ],
            ],
            [
                'icon' => 'access',
                'name' => 'Permisos',
                'subItems' => [
                    [                        
                        'name' => 'Listado',
                        'path' => route('tenant.permissions.index', absolute: false),
                        'pro'  => false
                    ],
                    [                        
                        'name' => 'Crear Permiso',
                        'path' => route('tenant.permissions.create', absolute: false),
                        'pro'  => false
                    ],
                    
                ],
            ]
        ];
    }    

    public static function getMenuGroups(): array
    {
        return [
            [
                'title' => 'Menu',
                'items' => self::getMainNavItems()
            ],
            [
                'title' => 'Administracion',
                'items' => self::getAdministrationItems()
            ],

            [
                'title' => 'Roles y Permisos',
                'items' => self::getRolesPermissionsItems()
            ],
           
        ];
    }

    public static function isActive($path): bool
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName): string
    {
        return IconHelper::get($iconName);
    }
}
