<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'country_code', 'phone', 'password', 'user_type', 'status', 'profile_picture',
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

    public function getCreatedAtAttribute($value) {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id')->withDefault(['name' => _lang('Default')]);
    }

    public function branch() {
        return $this->belongsTo('App\Models\Branch', 'branch_id')->withDefault(['name' => _lang('Default')]);
    }

    public function transactions() {
        return $this->hasMany('App\Models\Transaction', 'user_id')->with('currency')->orderBy('id', 'desc');
    }

    public function loans() {
        return $this->hasMany('App\Models\Loan', 'borrower_id')->with('currency')->orderBy('id', 'desc');
    }

    public function fixed_deposits() {
        return $this->hasMany('App\Models\FixedDeposit', 'user_id')->with('currency')->orderBy('id', 'desc');
    }

    public function support_tickets() {
        return $this->hasMany('App\Models\SupportTicket', 'user_id')->orderBy('id', 'desc');
    }
}
