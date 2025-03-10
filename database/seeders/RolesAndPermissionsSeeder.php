<?php declare(strict_types=1);

namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $roles = ['client', 'entreprise', 'chauffeur', 'agent', 'admin', 'super-admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Permissions générales
        $permissions = [
            'create reservation', 'edit reservation', 'delete reservation', 'view reservation',
            'assign chauffeur', 'manage users', 'manage payments', 'archive reservation'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Attribution des permissions
        Role::findByName('admin')->givePermissionTo(['manage users', 'view reservation']);
        Role::findByName('super-admin')->givePermissionTo(Permission::all());
    }
}
