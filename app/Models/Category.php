<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CategoryParentNullScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = [
        'name'
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withTimestamps();
    }

    public function subCategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }


    public function scopeParentIdIsNull($query)
    {
        return $query->where('parent_id', null);
    }

}
