<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimonialTranslation extends Model {

    protected $fillable = ['name', 'testimonial'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'testimonial_translations';
}