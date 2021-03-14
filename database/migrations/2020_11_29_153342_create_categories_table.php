<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_type_id')->nullable()->comment('カテゴリタイプID');
            $table->string('name')->comment('カテゴリ名');
            $table->string('slug')->comment('スラッグ');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('親カテゴリID');
            $table->unsignedBigInteger('sort')->nullable()->comment('並び順');
            $table->boolean('is_enable')->default(0)->comment('公開状態 0=非公開、1=公開');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE categories COMMENT "カテゴリテーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
