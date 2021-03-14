<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(AdminRolesTableSeeder::class);
        $this->call(PostTypesTableSeeder::class);
        $this->call(CategoryTypesSeeder::class);
        $this->call(CategoriesSeeder::class);
    }
}
