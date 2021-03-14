<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_type_id')->comment('投稿タイプID');
            $table->string('name')->comment('フィールド名');
            $table->string('type')->comment('フィールドタイプ');
            $table->string('key')->comment('キー');
            $table->longText('validate')->nullable()->comment('バリデータ');
            $table->longText('options')->nullable()->comment('オプション');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('親ID');
            $table->unsignedBigInteger('sort')->nullable()->comment('並び順');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE custom_field_metas COMMENT "カスタムフィールドメタ情報テーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_field_metas');
    }
}
