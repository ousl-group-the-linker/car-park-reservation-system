<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SriLankaDistrict extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sri_lanka_province_id',
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getNameAttribute()
    {
        return $this->attributes["name"] . " District";
    }

    public function Province()
    {
        return $this->belongsTo(SriLankaProvince::class, "sri_lanka_province_id");
    }
    public function Cities()
    {
        return $this->hasMany(SriLankaCity::class, "sri_lanka_district_id");
    }
}
