<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('subject');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('priority')->default(0);
            $table->bigInteger('created_user_id');
            $table->bigInteger('operator_id')->nullable();
            $table->bigInteger('closed_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('support_tickets');
    }
}
