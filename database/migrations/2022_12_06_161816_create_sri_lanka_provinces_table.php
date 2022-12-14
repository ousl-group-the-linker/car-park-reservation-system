<?php

use App\Models\SriLankaProvince;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSriLankaProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sri_lanka_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        foreach (CreateSriLankaCitiesTable::$sriLanka as $province => $districts) {
            SriLankaProvince::create([
                "name" => $province
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sri_lanka_provinces');
    }
}
