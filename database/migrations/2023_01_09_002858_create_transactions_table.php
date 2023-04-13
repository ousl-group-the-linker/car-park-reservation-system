<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("reference_id")->index()->nullable();
            $table->unsignedBigInteger("client_id");
            $table->float("amount", 15, 2);
            $table->float("final_balance", 15, 2)->nullable();
            $table->integer("status")->default(Transaction::$STATUS_NONE);
            $table->integer("intent")->nullable();
            $table->unsignedBigInteger("booking_id")->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
