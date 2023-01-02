<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 0;
    public const STATUS_CANCELLED = 100;
    public const STATUS_ONGOING = 200;
    public const STATUS_FINISHED = 300;

    public const STATUSES = [
        self::STATUS_PENDING => "Pending",
        self::STATUS_CANCELLED => "Cancelled",
        self::STATUS_ONGOING => "On Going",
        self::STATUS_FINISHED => "Finished",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "client_id",
        "branch_id",
        "estimated_start_time",
        "estimated_end_time",
        "real_start_time",
        "real_end_time",
        "hourly_rate",
        "status",
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
    protected $casts = [
        "estimated_start_time" => "datetime",
        "estimated_end_time" => "datetime",
        "real_start_time" => "datetime",
        "real_end_time" => "datetime",
        "hourly_rate" => "float"
    ];

    public function Branch()
    {
        return $this->belongsTo(Branch::class, "branch_id");
    }
    public function Client()
    {
        return $this->belongsTo(User::class, "client_id");
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
    public function isOnGoing()
    {
        return $this->status === self::STATUS_ONGOING;
    }
    public function isFinished()
    {
        return $this->status === self::STATUS_FINISHED;
    }

    public function estimatedHours(){
        return $this->estimated_end_time->diffInHours($this->estimated_start_time);
    }
    public function totalBookingHours(){
        return $this->real_end_time->diffInHours($this->real_start_time);
    }

    public function estimatedFee()
    {
        $estimatedDurationInHours = $this->estimated_end_time->diffInHours($this->estimated_start_time);

        return round(bcmul($estimatedDurationInHours, $this->hourly_rate, 4), 2);
    }

    public function totalFee()
    {
        $totalDurationInHours = $this->real_end_time->diffInHours($this->real_start_time);

        return round(bcmul($totalDurationInHours, $this->hourly_rate, 4), 2);
    }
}
