<?php

namespace Database\Seeders;

use App\Models\Menu as ModelsMenu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Menu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = Role::findByName('developer');

        $menus = [
            [
                'icon' => 'fa-solid fa-house-blank',
                'label_name' => 'Dashboard',
                'controller_name' => 'app\Http\Controllers\DashboardController',
                'route_name' => 'dashboard',
                'url' => 'dashboard',
                'sort_num' => '1',
                'is_divider' => false
            ],
            [
                'icon' => 'fa-solid fa-square-sliders',
                'label_name' => 'Access Settings',
                'controller_name' => null,
                'route_name' => 'rbac.index',
                'url' => 'rbac',
                'sort_num' => '2',
                'is_divider' => true
            ],
            [
                'icon' => 'fa-sharp fa-solid fa-square-list',
                'label_name' => 'Navigation Management',
                'controller_name' => 'app\Http\Controllers\Rbac\NavigationManagementController',
                'route_name' => 'rbac.nav.index',
                'url' => 'rbac/navigation-management',
                'sort_num' => '3',
                'is_divider' => false
            ],
            [
                'icon' => 'fa-solid fa-key',
                'label_name' => 'Permission Management',
                'controller_name' => 'app\Http\Controllers\Rbac\PermissionManagementController',
                'route_name' => 'rbac.permission.index',
                'url' => 'rbac/permission-management',
                'sort_num' => '4',
                'is_divider' => false
            ],
            [
                'icon' => 'fa-sharp fa-solid fa-shield-keyhole',
                'label_name' => 'Role Management',
                'controller_name' => 'app\Http\Controllers\Rbac\RoleManagementController',
                'route_name' => 'rbac.role.index',
                'url' => 'rbac/role-management',
                'sort_num' => '5',
                'is_divider' => false
            ],
            [
                'icon' => 'fa-solid fa-users-gear',
                'label_name' => 'User Management',
                'controller_name' => 'app\Http\Controllers\Rbac\UserManagementController',
                'route_name' => 'rbac.user.index',
                'url' => 'rbac/user-management',
                'sort_num' => '6',
                'is_divider' => false
            ]
        ];

        foreach ($menus as $menu) {
            $menuModel = ModelsMenu::updateOrCreate(
                [
                    'url' => $menu['url']
                ],
                [
                    'icon' => $menu['icon'],
                    'label_name' => $menu['label_name'],
                    'controller_name' => $menu['controller_name'],
                    'route_name' => $menu['route_name'],
                    'sort_num' => $menu['sort_num'],
                    'is_divider' => $menu['is_divider']
                ]
            );

            $developer->menus()->syncWithoutDetaching($menuModel->id);
        }
    }
}
