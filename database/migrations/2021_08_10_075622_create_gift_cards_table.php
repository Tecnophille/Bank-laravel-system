<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->bigInteger('currency_id');
            $table->decimal('amount', 10, 2);
            $table->tinyInteger('status')->default(0);
            $table->bigInteger('user_id')->nullable();
            $table->dateTime('used_at')->nullable();
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
        Schema::dropIfExists('gift_cards');
    }
}
