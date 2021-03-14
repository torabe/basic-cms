<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'category_type_id' => 1,
                'name' => 'お知らせ',
                'slug' => 'news',
                'is_enable' => 1
            ],
            [
                'category_type_id' => 1,
                'name' => 'イベント',
                'slug' => 'event',
                'is_enable' => 1
            ],
            [
                'category_type_id' => 1,
                'name' => 'その他',
                'slug' => 'other',
                'is_enable' => 1
            ],
        ];
        foreach ($categories as $key => $category) {
            DB::table('categories')->insert(array_merge($category, ['sort' => $key + 1]));
        }
    }
}
