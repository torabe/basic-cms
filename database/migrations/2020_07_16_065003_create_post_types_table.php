<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('投稿タイプ名');
            $table->string('slug')->comment('スラッグ');
            $table->text('description')->nullable()->comment('概要');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('親投稿タイプID');
            $table->string('admin_icon')->nullable()->comment('管理画面上のアイコン');
            $table->integer('per_page')->default(10)->comment('1ページ当たりの表示件数');
            $table->boolean('is_sortable')->default(0)->comment('並び替えの許可');
            $table->boolean('is_customize')->default(0)->comment('管理画面カスタマイズ 0=有、1=無');
            $table->unsignedBigInteger('sort')->nullable()->comment('並び順');
            $table->boolean('is_enable')->default(0)->comment('利用状況 0=停止中、1=利用中');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE post_types COMMENT "投稿タイプテーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_types');
    }
}
