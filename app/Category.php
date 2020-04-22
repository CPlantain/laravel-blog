<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
	use Sluggable;

    protected $fillable = ['title'];
	
    public function posts()
    {
    	return $this->hasMany(Post::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function clearPosts()
    {
        $this->posts->each(function($post){
            $post->setCategory(null);
        });

        return $this;
    }
}
