<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\PostType;

class AdminPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Admin::get() as $admin) {
            foreach (PostType::get() as $postType) {
                DB::table('admin_permissions')->insert([
                    'admin_id' => $admin->id,
                    'post_type_id' => $postType->id,
                    'permission' => 1
                ]);
            }
        }
    }
}
