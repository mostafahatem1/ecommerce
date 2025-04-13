<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administration', 'description' => 'Administrator', 'allowed_route' => 'admin',]);
        $supervisorRole = Role::create(['name' => 'supervisor', 'display_name' => 'Supervisor', 'description' => 'Supervisor', 'allowed_route' => 'admin',]);
        $customerRole = Role::create(['name' => 'customer', 'display_name' => 'Customer', 'description' => 'Customer', 'allowed_route' => null,]);

        $admin = User::create(['first_name' => 'Admin', 'last_name' => 'System', 'username' => 'admin', 'email' => 'admin@ecommerce.test', 'email_verified_at' => now(), 'mobile' => '966500000000', 'password' => bcrypt('123123123'), 'status' => 1, 'remember_token' => Str::random(10)]);
        $admin->attachRole($adminRole);

        $supervisor = User::create(['first_name' => 'Supervisor', 'last_name' => 'System',  'username' => 'supervisor', 'email' => 'supervisor@ecommerce.test', 'email_verified_at' => now(), 'mobile' => '966500000001', 'password' => bcrypt('123123123'), 'status' => 1, 'remember_token' => Str::random(10)]);
        $supervisor->attachRole($supervisorRole);

        $customer = User::create(['first_name' => 'Sami', 'last_name' => 'Mansour', 'username' => 'sami', 'email' => 'sami@gmail.com', 'email_verified_at' => now(), 'mobile' => '966500000002', 'password' => bcrypt('123123123'), 'status' => 1, 'remember_token' => Str::random(10)]);
        $customer->attachRole($customerRole);


        User::factory()->count(100)->hasAddresses(1)->create();

        $manageMain = Permission::create(['name' => 'main', 'display_name' => 'Main', 'route' => '/', 'module' => '/', 'as' => 'index', 'icon' => 'fas fa-home', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '1', 'appear' => '1', 'ordering' => '1',]);
        $manageMain->parent_show = $manageMain->id; $manageMain->save();

        createPermissionGroup('product categories', 'fas fa-file-archive', 5);
        createPermissionGroup('tags', 'fas fa-tags', 10);
        createPermissionGroup('products', 'fas fa-file-archive', 15);
        createPermissionGroup('product coupons', 'fas fa-ticket-alt', 20);
        createPermissionGroup('product reviews', 'fas fa-comment', 25);

        createPermissionGroup('customers', 'fas fa-users', 30);
        createPermissionGroup('Customer Addresses', 'fas fa-map-marked-alt', 35);

        createPermissionGroup('Countries', 'fas fa-globe', 45);
        createPermissionGroup('States', 'fas fa-map-marker-alt', 50);
        createPermissionGroup('Cities', 'fas fa-university', 55);

        createPermissionGroup('Shipping Companies', 'fas fa-truck', 90);


        // SUPERVISORS
        $mangeSupervisors = Permission::create(['name' => 'manage_supervisors', 'display_name' => 'supervisors', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.index', 'icon' => 'fas fa-user', 'parent' => '0', 'parent_original' => '0', 'sidebar_link' => '0', 'appear' => '1', 'ordering' => '1000',]);
        $mangeSupervisors->parent_show = $mangeSupervisors->id; $mangeSupervisors->save();
        $showsupervisors = Permission::create(['name' => 'show_supervisors', 'display_name' => 'supervisors', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.index', 'icon' => 'fas fa-user', 'parent' => $mangeSupervisors->id, 'parent_original' => $mangeSupervisors->id, 'parent_show' => $mangeSupervisors->id, 'sidebar_link' => '1', 'appear' => '1']);
        $createsupervisors = Permission::create(['name' => 'create_supervisors', 'display_name' => 'Create Customer', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.create', 'icon' => null, 'parent' => $mangeSupervisors->id, 'parent_original' => $mangeSupervisors->id, 'parent_show' => $mangeSupervisors->id, 'sidebar_link' => '1', 'appear' => '0']);
        $displaysupervisors = Permission::create(['name' => 'display_supervisors', 'display_name' => 'Show Customer', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.show', 'icon' => null, 'parent' => $mangeSupervisors->id, 'parent_original' => $mangeSupervisors->id, 'parent_show' => $mangeSupervisors->id, 'sidebar_link' => '1', 'appear' => '0']);
        $updatesupervisors = Permission::create(['name' => 'update_supervisors', 'display_name' => 'Update Customer', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.edit', 'icon' => null, 'parent' => $mangeSupervisors->id, 'parent_original' => $mangeSupervisors->id, 'parent_show' => $mangeSupervisors->id, 'sidebar_link' => '1', 'appear' => '0']);
        $deletesupervisors = Permission::create(['name' => 'delete_supervisors', 'display_name' => 'Delete Customer', 'route' => 'supervisors', 'module' => 'supervisors', 'as' => 'supervisors.destroy', 'icon' => null, 'parent' => $mangeSupervisors->id, 'parent_original' => $mangeSupervisors->id, 'parent_show' => $mangeSupervisors->id, 'sidebar_link' => '1', 'appear' => '0']);


    }
}
