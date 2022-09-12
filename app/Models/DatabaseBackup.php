<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseBackup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'database_backups';
	
	public function created_by(){
		return $this->belongsTo('App\Models\User','user_id')->withDefault();
	}
	
	public function getCreatedAtAttribute($value)
    {
		$date_format = get_date_format();
		$time_format = get_time_format();
        return \Carbon\Carbon::parse($value)->format("$date_format $time_format");
    }
}