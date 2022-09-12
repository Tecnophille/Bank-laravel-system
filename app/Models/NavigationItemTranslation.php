<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItemTranslation extends Model {

    protected $fillable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigation_item_translations';
}