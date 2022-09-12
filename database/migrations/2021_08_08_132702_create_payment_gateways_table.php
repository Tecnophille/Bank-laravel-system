<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('slug', 30);
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('parameters')->nullable();
            $table->string('currency', 3)->nullable();
            $table->text('supported_currencies')->nullable();
            $table->text('extra')->nullable();
            $table->decimal('exchange_rate', 10, 6)->nullable();
            $table->decimal('fixed_charge', 10, 2)->default(0);
            $table->decimal('charge_in_percentage', 10, 2)->default(0);
            $table->decimal('minimum_amount', 10, 2)->default(0);
            $table->decimal('maximum_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('payment_gateways');
    }
}
