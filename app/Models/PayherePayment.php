<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayherePayment extends Model
{
    use HasFactory;

    public static $STATUS_SUCCESS = "2";
    public static $STATUS_PENDING = "0";
    public static $STATUS_CANCELED = "-1";
    public static $STATUS_FAILED = "-2";
    public static $STATUS_CHARGED_BACK = "-3";


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'gateway_payment_id',
        'payhere_amount',
        'status_code',
        'method',
        'status_message',
        'card_holder_name',
        'card_no',
        'card_expiry'
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

    public function getStatusTextAttribute()
    {
        switch ($this->status_code) {
            case self::$STATUS_SUCCESS:
                return "Success";
            case self::$STATUS_PENDING:
                return "Pending";
            case self::$STATUS_CANCELED:
                return "Canceled";
            case self::$STATUS_FAILED:
                return "Failed";
            case self::$STATUS_CHARGED_BACK:
                return "Refunded";
            default:
                return "Unknown";
        }
    }

    public function isStatusSuccess()
    {
        return $this->status_code == self::$STATUS_SUCCESS;
    }
    public function isStatusPending()
    {
        return $this->status_code == self::$STATUS_PENDING;
    }
    public function isStatusCanceled()
    {
        return $this->status_code == self::$STATUS_CANCELED;
    }
    public function isStatusFailed()
    {
        return $this->status_code == self::$STATUS_FAILED;
    }
    public function isStatusRefunded()
    {
        return $this->status_code == self::$STATUS_CHARGED_BACK;
    }
}
