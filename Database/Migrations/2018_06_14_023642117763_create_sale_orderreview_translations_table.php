<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderReviewTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale__orderreview_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('orderreview_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['orderreview_id', 'locale']);
            $table->foreign('orderreview_id')->references('id')->on('sale__orderreviews')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale__orderreview_translations', function (Blueprint $table) {
            $table->dropForeign(['orderreview_id']);
        });
        Schema::dropIfExists('sale__orderreview_translations');
    }
}
