<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderRefundTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale__orderrefund_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('orderrefund_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['orderrefund_id', 'locale']);
            $table->foreign('orderrefund_id')->references('id')->on('sale__orderrefunds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale__orderrefund_translations', function (Blueprint $table) {
            $table->dropForeign(['orderrefund_id']);
        });
        Schema::dropIfExists('sale__orderrefund_translations');
    }
}
