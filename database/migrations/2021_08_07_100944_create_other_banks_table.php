<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherBanksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('other_banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('swift_code', 20);
            $table->string('bank_country');
            $table->bigInteger('bank_currency');
            $table->decimal('minimum_transfer_amount', 10, 2);
            $table->decimal('maximum_transfer_amount', 10, 2);
            $table->decimal('fixed_charge', 10, 2);
            $table->decimal('charge_in_percentage', 10, 2);
            $table->text('descriptions')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('other_banks');
    }
}
