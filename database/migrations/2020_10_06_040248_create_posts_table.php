<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_type_id')->nullable()->comment('post_typesテーブルのID');
            $table->dateTime('publish_at')->nullable()->comment('公開日');
            $table->dateTime('unpublish_at')->nullable()->comment('公開終了日');
            $table->string('slug')->nullable()->comment('スラッグ');
            $table->string('title')->nullable()->comment('タイトル');
            $table->text('description')->nullable()->comment('概要');
            $table->unsignedBigInteger('admin_id')->nullable()->comment('投稿・更新者ID');
            $table->unsignedBigInteger('sort')->nullable()->comment('並び順');
            $table->boolean('is_enable')->default(0)->comment('公開状態 0=非公開、1=公開');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE posts COMMENT "投稿テーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
