<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';
	
	public function permissions(){
		return $this->hasMany('App\Models\AccessControl','role_id');
	}
}