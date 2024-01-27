<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role\Role;
use App\Models\Role\RolePermission;
use App\Models\Role\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(['id' => ADMIN, 'name' => 'admin'],['name' => 'admin']);
        Role::updateOrCreate(['id' => USER, 'name' => 'user'],['name' => 'user']);

        Permission::updateOrCreate(['name' => 'create_transaction', 'description' => 'Create Transaction']);
        $viewTransactionPermission=Permission::updateOrCreate(['name' => 'view_transactions', 'description' => 'View Transactions']);
        Permission::updateOrCreate(['name' => 'record_payment', 'description' => 'Record Payment']);
        Permission::updateOrCreate(['name' => 'generate_reports', 'description' => 'Generate Reports']);
      
        ///Seeding all permissions to admin role
        foreach (Permission::get() as $permission) {
            RolePermission::updateOrCreate(['role_id' => ADMIN, 'permission_id' => $permission->id]);
        }
        //Seeding view transaction to user role
        RolePermission::updateOrCreate(['role_id' => USER, 'permission_id' => $viewTransactionPermission->id]);

       
    }
}
