<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('カテゴリタイプ名');
            $table->string('slug')->comment('スラッグ');
            $table->string('select')->nullable()->comment('選択方式');
            $table->boolean('is_multiple')->default(0)->comment('複数選択可');
            $table->boolean('is_enable')->default(0)->comment('利用状況 0=停止中、1=利用中');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE category_types COMMENT "カテゴリタイプテーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_types');
    }
}
