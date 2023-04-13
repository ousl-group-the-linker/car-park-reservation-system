<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "name",
        "email",
        "contact_number",
        "address_line_1",
        "address_line_2",
        "address_line_3",
        "address_city_id",
        "parking_slots",
        "manager_id",
        "hourly_rate"
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

    public function City()
    {
        return $this->belongsTo(SriLankaCity::class, "address_city_id");
    }

    public function Manager()
    {
        return $this->belongsTo(User::class, "manager_id");
    }
    public function Employees()
    {
        return $this->hasMany(User::class, "work_for_branch_id");
    }

    public function Bookings()
    {
        return $this->hasMany(Booking::class, "branch_id");
    }

    public function getAddressTextAttribute()
    {
        $chunks = [
            $this->address_line_1,
            $this->address_line_2,
            $this->address_line_3,
            $this->City->name
        ];

        $chunks = array_filter($chunks);
        return join(", ", $chunks);
    }

    public function reservedSlots()
    {
        return $this->Bookings()->today()
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->onGoing();
                });
                $query->orWhere(function ($query) {
                    $query->pending();
                });
            });
    }
    public function reservedPersentage()
    {
        return ($this->reservedSlots()->count() / $this->parking_slots) * 100;
    }
}
