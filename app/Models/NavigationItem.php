<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model {
    use Translatable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_items';

    public function child_items() {
        return $this->hasMany('App\Models\NavigationItem', 'parent_id');
    }

    public function page() {
        return $this->belongsTo('App\Models\Page', 'page_id')->withDefault();
    }
}