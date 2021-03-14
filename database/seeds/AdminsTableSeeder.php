<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => '開発者',
            'login_id' => \Config::get('admin.identifier.developer'),
            'role_id' => 1,
            'email' => '',
            'password' => Hash::make('1234'),
            'is_enable' => 1
        ]);
        DB::table('admins')->insert([
            'name' => '管理者',
            'login_id' => \Config::get('admin.identifier.administrator'),
            'role_id' => 2,
            'email' => '',
            'password' => Hash::make('1234'),
            'is_enable' => 1
        ]);
        DB::table('admins')->insert([
            'name' => '編集者01',
            'login_id' => 'editor01',
            'role_id' => 3,
            'email' => '',
            'password' => Hash::make('1234'),
            'is_enable' => 1
        ]);
        DB::table('admins')->insert([
            'name' => '編集者02',
            'login_id' => 'editor02',
            'role_id' => 3,
            'email' => '',
            'password' => Hash::make('1234'),
            'is_enable' => 0
        ]);
    }
}
