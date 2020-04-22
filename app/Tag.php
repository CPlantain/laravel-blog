<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{
	use Sluggable;

    protected $fillable = ['title'];
	
    public function posts()
    {
    	return $this->belongsToMany(
    		Post::class,
    		'post_tags',
    		'tag_id',
    		'post_id'
    	);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function clearTagsRelations()
    {
        DB::table('post_tags')->where('tag_id', $this->id)->delete();
        
        return $this;
    }
}
