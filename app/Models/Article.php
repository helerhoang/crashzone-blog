<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';

    protected $fillable = ['title', 'title_seo', 'description', 'content'];

    protected $hidden = [
        'deleted_at'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function images()
    {
        return $this->belongsToMany(Image::class);
    }


}
