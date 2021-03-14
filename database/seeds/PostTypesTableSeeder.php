<?php

use Illuminate\Database\Seeder;

class PostTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $postTypes = [
            [
                'name' => 'トピックス',
                'slug' => 'topics',
                'parent_id' => null,
                'admin_icon' => 'mdi-newspaper-variant-multiple-outline',
                'is_customize' => false,
                'is_enable' => 1
            ],
        ];
        foreach ($postTypes as $key => $postType) {
            DB::table('post_types')->insert(array_merge($postType, ['sort' => $key + 1]));
        }
    }
}
