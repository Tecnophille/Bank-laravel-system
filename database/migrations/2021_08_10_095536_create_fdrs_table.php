<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFdrsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('fdrs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fdr_plan_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->decimal('deposit_amount', 10, 2);
            $table->decimal('return_amount', 10, 2);
            $table->text('attachment')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->default(0);
            $table->date('approved_date')->nullable();
            $table->date('mature_date')->nullable();
            $table->bigInteger('transaction_id')->nullable();
            $table->bigInteger('approved_user_id')->nullable();
            $table->bigInteger('created_user_id')->nullable();
            $table->bigInteger('updated_user_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('fdrs');
    }
}
