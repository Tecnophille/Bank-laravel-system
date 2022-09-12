<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model {

    protected $fillable = ['title', 'body'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_translations';

}