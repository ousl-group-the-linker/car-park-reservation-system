<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public static $ROLE_SUPER_ADMIN = "100";
    public static $ROLE_MANAGER = "80";
    public static $ROLE_COUNTER = "60";
    public static $ROLE_USER = "20";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'address_city_id',
        'contact_number',
        'work_for_branch_id', //employee relationship
        'password',
        'role',
        'is_activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function City()
    {
        return $this->belongsTo(SriLankaCity::class, "address_city_id");
    }

    public function getRoleTextAttribute()
    {
        switch ($this->role) {
            case self::$ROLE_SUPER_ADMIN:
                return "Super Admin";
            case self::$ROLE_MANAGER:
                return "Manager";
            case self::$ROLE_COUNTER:
                return "Counter";
            case self::$ROLE_USER:
                return "User";
        }
    }
    public function isSuperAdminAccount()
    {
        return $this->role == self::$ROLE_SUPER_ADMIN;
    }
    public function isManagerAccount()
    {
        return $this->role == self::$ROLE_MANAGER;
    }
    public function isCounterAccount()
    {
        return $this->role == self::$ROLE_COUNTER;
    }
    public function isUserAccount()
    {
        return $this->role == self::$ROLE_USER;
    }

    public function ManageBranch()
    {
        return $this->hasOne(Branch::class, "manager_id");
    }

    public function WorkForBranch(){
        return $this->belongsTo(Branch::class, "work_for_branch_id");
    }
}
