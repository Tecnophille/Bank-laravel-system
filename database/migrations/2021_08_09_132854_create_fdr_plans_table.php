<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFdrPlansTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fdr_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->decimal('minimum_amount', 10, 2);
            $table->decimal('maximum_amount', 10, 2);
            $table->decimal('interest_rate', 10, 2);
            $table->integer('duration');
            $table->string('duration_type', 15);
            $table->tinyInteger('status')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('fdr_plans');
    }
}
