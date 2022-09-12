<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawRequestsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('method_id');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('withdraw_requests');
    }
}
