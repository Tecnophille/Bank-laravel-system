<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model {
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigations';

    public function navigationItems() {
        return $this->hasMany('App\Models\NavigationItem')->orderBy("position", "asc");
    }
}