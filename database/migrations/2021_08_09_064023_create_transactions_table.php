<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('currency_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->string('dr_cr', 2);
            $table->string('type', 20);
            $table->string('method', 20);
            $table->tinyInteger('status');
            $table->text('note')->nullable();
            $table->bigInteger('loan_id')->nullable();
            $table->bigInteger('ref_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('other_bank_id')->nullable();
            $table->bigInteger('gateway_id')->nullable();
            $table->bigInteger('created_user_id')->nullable();
            $table->bigInteger('updated_user_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->text('transaction_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transactions');
    }
}
