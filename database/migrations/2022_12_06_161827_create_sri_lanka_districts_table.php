<?php

use App\Models\SriLankaDistrict;
use App\Models\SriLankaProvince;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSriLankaDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sri_lanka_districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sri_lanka_province_id');
            $table->string('name');
            $table->timestamps();
        });

        foreach (CreateSriLankaCitiesTable::$sriLanka as $province => $districts) {
            $provinceId = SriLankaProvince::where("name", $province)->first()->id;

            foreach ($districts as $district => $cities) {
                SriLankaDistrict::create([
                    "sri_lanka_province_id" => $provinceId,
                    "name" => $district
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sri_lanka_districts');
    }
}
