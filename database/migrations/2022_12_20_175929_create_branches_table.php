<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {

            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("contact_number");
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('address_line_3')->nullable();
            $table->unsignedBigInteger('address_city_id');
            $table->unsignedBigInteger("parking_slots");
            $table->float("hourly_rate")->default(0);
            $table->unsignedBigInteger("manager_id")->nullable();
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
        Schema::dropIfExists('branches');
    }
}
