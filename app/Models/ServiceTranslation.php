<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model {

    protected $fillable = ['title', 'body'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_translations';
}