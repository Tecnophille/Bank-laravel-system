<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'support_tickets';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    public function created_by() {
        return $this->belongsTo('App\Models\User', 'created_user_id')->withDefault();
    }

    public function operator() {
        return $this->belongsTo('App\Models\User', 'operator_id')->withDefault();
    }

    public function closed_by() {
        return $this->belongsTo('App\Models\User', 'closed_user_id')->withDefault();
    }

    public function messages() {
        return $this->hasMany('App\Models\SupportMessage', 'support_ticket_id');
    }

    public function getCreatedAtAttribute($value) {
        $date_format = get_date_format();
        $time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }

    protected static function booted() {
        $user = auth()->user();
        static::addGlobalScope('operator_id', function (Builder $builder) use ($user) {
            if ($user->user_type == 'user') {
                return $builder->where('operator_id', $user->id)
                    ->orWhere('created_user_id', $user->id);

            }
        });
    }
}