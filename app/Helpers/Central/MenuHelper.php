<?php

namespace App\Helpers\Central;

use App\Helpers\Function\IconHelper;

class MenuHelper
{
    public static function getMainNavItems(): array
    {        
        return [
            [
                'icon' => 'home',
                'name' => 'Inicio',
                'path' => route('central.home', absolute: false),
            ],
            [
                'icon' => 'data-base',
                'name' => 'Inquilinos',
                'subItems' => [
                    [
                        'name' => 'Listado',
                        'path' => route('central.tenants.index', ['status'=>'active'], absolute: false),
                        'pro' => false
                    ],
                    [
                        'name' => 'Crear Inquilino',
                        'path' => route('central.tenants.create', absolute: false),
                        'pro' => false
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
                'name' => 'Usuarios',
                'subItems' => [
                    [
                        'name' => 'Listado',
                        'path' => route('central.users.index', absolute: false),
                        'permission' => 'central.users.index',
                        'pro' => false
                    ],
                    [
                        'name' => 'Crear Usuario',
                        'path' => route('central.users.create', absolute: false),
                        'permission' => 'central.users.create',
                        'pro' => false
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
                        'path' => route('central.roles.index', absolute: false),
                        'pro'  => false
                    ],
                    [                        
                        'name' => 'Crear Roles',
                        'path' => route('central.roles.create', absolute: false),
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
                        'path' => route('central.permissions.index', absolute: false),
                        'pro'  => false
                    ],
                    [                        
                        'name' => 'Crear Permiso',
                        'path' => route('central.permissions.create', absolute: false),
                        'pro'  => false
                    ],
                    
                ],
            ]
        ];
    }    

    public static function getSupportItems(): array
    {
        return [            
            [
                'icon' => 'support',
                'name' => 'Errores',
                'path' => route('central.errors.index', absolute: false),
                'pro' => false
            ],
        ];
    }

    // public static function getMenuGroups(): array
    // {   
             
    //     return [
    //         [
    //             'title' => 'Menu',
    //             'items' => self::getMainNavItems()
    //         ],
    //         [
    //             'title' => 'Administration',
    //             'items' => self::getAdministrationItems()
    //         ],
    //         [
    //             'title' => 'Roles y Permisos',
    //             'items' => self::getRolesPermissionsItems()
    //         ],
    //         [
    //             'title' => 'Soporte',
    //             'items' => self::getSupportItems()
    //         ]
    //     ];
    // }
    public static function getMenuGroups(): array
    {
        $user = auth('web')->user();

        if (!$user) {
            return [];
        }

        $groups = [
            [
                'title' => 'Menu',
                'items' => self::getMainNavItems(),
            ],
            [
                'title' => 'Administration',
                'items' => self::getAdministrationItems(),
            ],
            [
                'title' => 'Roles y Permisos',
                'items' => self::getRolesPermissionsItems(),
            ],
            [
                'title' => 'Soporte',
                'items' => self::getSupportItems(),
            ],
        ];

        foreach ($groups as &$group) {
            $items = [];

            foreach ($group['items'] as $item) {

                // Verificar permiso del elemento principal
                if (
                    isset($item['permission']) &&
                    !$user->can($item['permission'])
                ) {
                    continue;
                }

                // Filtrar elementos del submenú
                if (isset($item['subItems'])) {

                    $item['subItems'] = array_values(
                        array_filter(
                            $item['subItems'],
                            fn (array $subItem) =>
                                !isset($subItem['permission']) ||
                                $user->can($subItem['permission'])
                        )
                    );

                    // Ocultar menú padre sin opciones visibles
                    if (empty($item['subItems'])) {
                        continue;
                    }
                }

                $items[] = $item;
            }

            $group['items'] = $items;
        }

        unset($group);

        // Ocultar grupos que quedaron sin elementos
        return array_values(
            array_filter(
                $groups,
                fn (array $group) => !empty($group['items'])
            )
        );
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
