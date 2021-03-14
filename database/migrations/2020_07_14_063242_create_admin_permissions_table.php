<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->comment('管理ユーザーID');
            $table->unsignedBigInteger('post_type_id')->comment('投稿タイプID');
            $table->boolean('permission')->default(0)->comment('編集許可 0=不許可、1=許可');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE admin_permissions COMMENT "管理ユーザー編集許可テーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permissions');
    }
}
