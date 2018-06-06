<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCommentTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale__comment_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('comment_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['comment_id', 'locale']);
            $table->foreign('comment_id')->references('id')->on('sale__comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale__comment_translations', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
        });
        Schema::dropIfExists('sale__comment_translations');
    }
}
