<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayherePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payhere_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->string('gateway_payment_id')->nullable();
            $table->float('payhere_amount', 15, 2);
            $table->integer('status_code')->nullable();
            $table->string('method')->nullable();
            $table->text('status_message')->nullable();
            $table->string('card_holder_name')->nullable();
            $table->string('card_no')->nullable();
            $table->string('card_expiry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payhere_payments');
    }
}
