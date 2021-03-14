<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('権限名');
            $table->string('identifier')->comment('識別子');
            $table->unsignedBigInteger('sort')->nullable()->comment('並び順');
            $table->boolean('is_enable')->default(0)->comment('利用状況 0=停止中、1=利用中');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE admin_roles COMMENT "管理ユーザー権限テーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_roles');
    }
}
