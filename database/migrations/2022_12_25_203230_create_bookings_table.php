<?php

use App\Models\Booking;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("client_id");
            $table->unsignedBigInteger("branch_id");

            $table->dateTime("estimated_start_time");
            $table->dateTime("estimated_end_time");

            $table->dateTime("real_start_time")->nullable();
            $table->dateTime("real_end_time")->nullable();

            $table->double("hourly_rate", null, 2);

            $table->integer("status")->default(Booking::STATUS_PENDING);

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
        Schema::dropIfExists('bookings');
    }
}
