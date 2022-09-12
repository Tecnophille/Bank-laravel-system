<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('currency_id');
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('status');
            $table->text('description')->nullable();
            $table->bigInteger('sender_id');
            $table->bigInteger('receiver_id');
            $table->bigInteger('transaction_id')->nullable();
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
        Schema::dropIfExists('payment_requests');
    }
}
