<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleSaleOrderTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale__saleorder_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('saleorder_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['saleorder_id', 'locale']);
            $table->foreign('saleorder_id')->references('id')->on('sale__saleorders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale__saleorder_translations', function (Blueprint $table) {
            $table->dropForeign(['saleorder_id']);
        });
        Schema::dropIfExists('sale__saleorder_translations');
    }
}
