<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('login_id', 50)->comment('ログインID');
            $table->string('name', 50)->comment('担当者姓名');
            $table->string('ip', 50)->comment('IPアドレス');
            $table->string('user_agent')->comment('ユーザーエージェント');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE logs COMMENT "操作ログテーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
