<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public static $STATUS_SUCCESS = "100";
    public static $STATUS_FAILED = "80";
    public static $STATUS_PENDING = "60";
    public static $STATUS_REFUNDED = "-60";
    public static $STATUS_NONE = "-100";

    public static $INTENT_RELOAD = "60";
    public static $INTENT_BOOKING = "60";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "reference_id",
        "client_id",
        'amount',
        'final_balance',
        'status',
        'intent',
        'booking_id'
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
        "created_at" => "datetime",
        "updated_at" => "datetime",
    ];

    public function isSuccess()
    {
        return $this->status == Transaction::$STATUS_SUCCESS;
    }
    public function isFailed()
    {
        return $this->status == Transaction::$STATUS_FAILED;
    }
    public function isPending()
    {
        return $this->status == Transaction::$STATUS_PENDING;
    }

    public function scopeNotStatus($query, $status)
    {
        return $query->where("status", "!=", $status);
    }
    public function scopeStatus($query, $status)
    {
        return $query->where("status", $status);
    }
    public function scopeIntent($query, $intent)
    {
        return $query->where("intent", $intent);
    }


    public function PayherePayment()
    {
        return $this->hasOne(PayherePayment::class, "transaction_id");
    }

    public function Client()
    {
        return $this->belongsTo(User::class, "client_id");
    }
}
