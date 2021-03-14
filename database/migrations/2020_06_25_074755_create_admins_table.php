<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('ユーザー名');
            $table->string('login_id')->comment('ログインID');
            $table->integer('role_id')->comment('ユーザー権限ID');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('password')->comment('パスワード');
            $table->unsignedBigInteger('sort')->nullable()->comment('並び順');
            $table->boolean('is_enable')->default(0)->comment('利用状況 0=停止中、1=利用中');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE admins COMMENT "管理ユーザーテーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
