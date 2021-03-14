<?php

use Illuminate\Database\Seeder;

class AdminRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_roles')->insert([
            'name' => '開発者',
            'identifier' => \Config::get('admin.identifier.developer'),
            'is_enable' => 1,
            'sort' => 1
        ]);
        DB::table('admin_roles')->insert([
            'name' => '管理者',
            'identifier' => \Config::get('admin.identifier.administrator'),
            'is_enable' => 1,
            'sort' => 2
        ]);
        DB::table('admin_roles')->insert([
            'name' => '編集者',
            'identifier' => \Config::get('admin.identifier.editor'),
            'is_enable' => 1,
            'sort' => 3
        ]);
        DB::table('admin_roles')->insert([
            'name' => '投稿者',
            'identifier' => \Config::get('admin.identifier.contributor'),
            'is_enable' => 0,
            'sort' => 4
        ]);
    }
}
