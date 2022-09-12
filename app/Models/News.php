<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class News extends Model {
    use Translatable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    public function setSlugAttribute($value) {
        $this->attributes['slug'] = $this->generateSlug($value);
    }

    public function author() {
        return $this->belongsTo('App\Models\User', 'user_id')->withDefault();
    }

    private function generateSlug($value) {
        $slug   = mb_strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value)));
        $count  = News::where('slug', 'LIKE', $slug . '%')->count();
        $suffix = $count > 0 ? $count + 1 : "";
        $slug .= $suffix;
        return $slug;
    }

}