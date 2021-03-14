<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id')->comment('投稿ID');
            $table->unsignedBigInteger('meta_id')->comment('メタID');
            $table->longText('value')->nullable()->comment('値');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE custom_fields COMMENT "カスタムフィールドテーブル"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_fields');
    }
}
