<?php

use Illuminate\Database\Seeder;

class CategoryTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryType = [
            [
                'name' => 'カテゴリ',
                'slug' => 'category',
                'select' => 'select',
                'is_multiple' => true,
                'is_enable' => 1
            ],
        ];
        foreach ($categoryType as $key => $categoryType) {
            DB::table('category_types')->insert($categoryType);
        }
        DB::table('category_type_post_type')->insert(['post_type_id' => 1, 'category_type_id' => 1]);
    }
}
