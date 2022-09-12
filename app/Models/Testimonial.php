<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model {
    use Translatable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'testimonials';
}