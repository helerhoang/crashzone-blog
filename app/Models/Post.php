<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mews\Purifier\Facades\Purifier;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = ['title', 'slug', 'description', 'content'];

    protected $hidden = [
        'deleted_at'
    ];

    public function setContentAttribute($value)
    {
        return $this->attributes['content'] = Purifier::clean($value);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withCount('posts');
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
        return $this->belongsToMany(Image::class)->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }


}
