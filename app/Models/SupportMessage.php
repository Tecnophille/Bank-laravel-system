<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'support_ticket_messages';

    public function sender() {
        return $this->belongsTo('App\Models\User', 'sender_id')->withDefault();
    }

    public function ticket() {
        return $this->belongsTo('App\Models\SupportTicket', 'support_ticket_id')->withDefault();
    }
}