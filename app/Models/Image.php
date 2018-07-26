<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['name', 'alt', 'slug', 'path', 'extension', 'mime_type', 'size'];
    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}
