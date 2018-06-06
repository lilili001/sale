<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderReturnTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale__orderreturn_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('orderreturn_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['orderreturn_id', 'locale']);
            $table->foreign('orderreturn_id')->references('id')->on('sale__orderreturns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale__orderreturn_translations', function (Blueprint $table) {
            $table->dropForeign(['orderreturn_id']);
        });
        Schema::dropIfExists('sale__orderreturn_translations');
    }
}
