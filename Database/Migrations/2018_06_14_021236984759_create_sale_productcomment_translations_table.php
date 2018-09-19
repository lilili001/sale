<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductCommentTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale__productcomment_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('productcomment_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['productcomment_id', 'locale']);
            $table->foreign('productcomment_id')->references('id')->on('sale__productcomments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale__productcomment_translations', function (Blueprint $table) {
            $table->dropForeign(['productcomment_id']);
        });
        Schema::dropIfExists('sale__productcomment_translations');
    }
}
