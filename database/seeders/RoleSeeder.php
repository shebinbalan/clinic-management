<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'doctor']);
        Role::create(['name' => 'patient']);

        // Create permissions
        $permissions = [
            'manage-users',
            'manage-doctors',
            'manage-appointments',
            'view-appointments',
            'book-appointments',
            'view-patients',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo([
            'manage-users',
            'manage-doctors',
            'manage-appointments',
            'view-appointments',
            'view-patients',
        ]);

        $doctorRole = Role::findByName('doctor');
        $doctorRole->givePermissionTo([
            'view-appointments',
            'manage-appointments',
        ]);

        $patientRole = Role::findByName('patient');
        $patientRole->givePermissionTo([
            'book-appointments',
            'view-appointments',
        ]);
    }
}