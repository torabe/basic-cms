<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_post', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->comment('投稿ID');
            $table->unsignedBigInteger('category_id')->comment('カテゴリID');
        });

        DB::statement('ALTER TABLE category_post COMMENT "カテゴリ-投稿中間テーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_post');
    }
}
