<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTypePostTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_type_post_type', function (Blueprint $table) {
            $table->unsignedBigInteger('post_type_id')->comment('投稿タイプID');
            $table->unsignedBigInteger('category_type_id')->comment('カテゴリタイプID');
        });

        DB::statement('ALTER TABLE category_type_post_type COMMENT "カテゴリタイプ-投稿タイプ中間テーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_type_post_type');
    }
}
