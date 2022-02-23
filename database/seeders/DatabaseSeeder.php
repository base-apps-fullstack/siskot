<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('roles')->truncate();
        \DB::table('user_roles')->truncate();
        \DB::table('users')->truncate();
        \DB::table('menus')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /** ROLES */
        \DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Admin',
            'permissions' => json_encode(['app.dashboard', 'app.history-text', 'app.history-professional', 'app.verify-text', 'app.verify-professional', 'app.role-access.lihat', 'app.role-access.buat', 'app.role-access.ubah', 'app.role-access.hapus', 'app.user.lihat', 'app.user.buat', 'app.user.ubah', 'app.user.hapus']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Agent - Lender',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('roles')->insert([
            'id' => 3,
            'name' => 'Agent - Borrower',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        /** USER */
        \DB::table('users')->insert([
            'id' => 1,
            'username' => appencrypt('admin'),
            'email' => 'admin@mail.com',
            'fullname' => 'Administrator',
            'status' => 'active',
            'password' => \Hash::make('admin123'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('user_roles')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        /** MENU */
        \DB::table('menus')->insert([
            'id' => 1,
            'name' => 'Dashboard',
            'classification' => 'Dashboard',
            'icon' => 'dashboard',
            'url' => '/dashboard',
            'order_classification' => 1,
            'order_inner_classification' => 1,
            'is_collapse' => 0,
            'actions' => json_encode(['app.dashboard']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('menus')->insert([
            'id' => 2,
            'name' => 'Text',
            'classification' => 'History',
            'icon' => 'history',
            'url' => '/history/text',
            'order_classification' => 2,
            'order_inner_classification' => 1,
            'is_collapse' => 0,
            'actions' => json_encode(['app.history-text']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('menus')->insert([
            'id' => 3,
            'name' => 'Professional',
            'classification' => 'History',
            'icon' => 'history',
            'url' => '/history/professional',
            'order_classification' => 2,
            'order_inner_classification' => 2,
            'is_collapse' => 0,
            'actions' => json_encode(['app.history-professional']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('menus')->insert([
            'id' => 4,
            'name' => 'Text',
            'classification' => 'Verify',
            'icon' => 'dvr',
            'url' => '/verify/text',
            'order_classification' => 3,
            'order_inner_classification' => 1,
            'is_collapse' => 0,
            'actions' => json_encode(['app.verify-text']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('menus')->insert([
            'id' => 5,
            'name' => 'Professional',
            'classification' => 'Verify',
            'icon' => 'dvr',
            'url' => '/verify/professional',
            'order_classification' => 3,
            'order_inner_classification' => 2,
            'is_collapse' => 0,
            'actions' => json_encode(['app.verify-professional']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        /** ROLE */
        \DB::table('menus')->insert([
            'id' => 6,
            'name' => 'Role Access',
            'classification' => 'Settings',
            'icon' => 'vpn_key',
            'url' => '/settings/role-access',
            'order_classification' => 99,
            'order_inner_classification' => 1,
            'is_collapse' => 0,
            'actions' => json_encode(['app.role-access.lihat', 'app.role-access.buat', 'app.role-access.ubah', 'app.role-access.hapus']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        \DB::table('menus')->insert([
            'id' => 7,
            'name' => 'Account Management',
            'classification' => 'Settings',
            'icon' => 'people',
            'url' => '/settings/users',
            'order_classification' => 99,
            'order_inner_classification' => 2,
            'is_collapse' => 0,
            'actions' => json_encode(['app.user.lihat', 'app.user.buat', 'app.user.ubah', 'app.user.hapus']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
