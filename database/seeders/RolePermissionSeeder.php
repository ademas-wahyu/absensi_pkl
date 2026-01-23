<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /** @var \Spatie\Permission\PermissionRegistrar $permissionRegistrar */
        $permissionRegistrar = app()[
            \Spatie\Permission\PermissionRegistrar::class
        ];
        $permissionRegistrar->forgetCachedPermissions();

        $permissions = [
            "view dashboard",
            "manage users",
            "manage students",
            "view attendance",
            "create attendance",
            "edit attendance",
            "delete attendance",
            "view jurnal",
            "create jurnal",
            "edit jurnal",
            "delete jurnal",
            "view reports",
            "export reports",
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(["name" => $permission]);
        }

        $adminRole = Role::query()->firstOrCreate(["name" => "admin"]);
        $adminRole->givePermissionTo(Permission::all());

        $studentPermissions = [
            "view dashboard",
            "view attendance",
            "create attendance",
            "view jurnal",
            "create jurnal",
            "edit jurnal",
        ];

        $muridRole = Role::query()->firstOrCreate(["name" => "murid"]);
        $muridRole->givePermissionTo($studentPermissions);

        // Mentor Config
        $mentorPermissions = [
            "view dashboard",
            "view attendance",
            "view jurnal",
            "view reports",
        ];

        $mentorRole = Role::query()->firstOrCreate(["name" => "mentor"]);
        $mentorRole->givePermissionTo($mentorPermissions);
    }
}
