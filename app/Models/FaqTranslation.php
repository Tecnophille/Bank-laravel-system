<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqTranslation extends Model {

    protected $fillable = ['question', 'answer'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faq_translations';
}