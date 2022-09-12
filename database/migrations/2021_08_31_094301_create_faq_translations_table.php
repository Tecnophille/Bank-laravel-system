<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqTranslationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('faq_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('faq_id')->unsigned();
            $table->string('locale');
            $table->text('question');
            $table->text('answer');

            $table->unique(['faq_id', 'locale']);
            $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('faq_translations');
    }
}
