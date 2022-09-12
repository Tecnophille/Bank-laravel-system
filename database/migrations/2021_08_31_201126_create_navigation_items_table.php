<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNavigationItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('navigation_id')->unsigned()->index();
            $table->string('type', 20);
            $table->bigInteger('page_id')->unsigned()->nullable();
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('target');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->integer('position')->unsigned()->nullable();
            $table->boolean('status');
            $table->string('css_class')->nullable();
            $table->string('css_id')->nullable();
            $table->timestamps();

            $table->foreign('navigation_id')->references('id')->on('navigations')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('navigation_items')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('navigation_items');
    }
}
