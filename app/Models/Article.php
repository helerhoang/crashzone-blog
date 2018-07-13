<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    public function categories()
    {
       return $this->belongsToMany(Category::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
