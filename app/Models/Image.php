<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }
}
