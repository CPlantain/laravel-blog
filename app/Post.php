<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
	use Sluggable;

	const IS_DRAFT = 0;
	const IS_PUBLIC = 1;

	protected $fillable = ['title', 'description', 'content', 'date'];

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function path()
    {
        return route('posts.show', $this);
    }

    public function category()
    {
    	return $this->belongsTo(Category::class, 'category_id');
    }

    public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
    	return $this->belongsToMany(
    		Tag::class,
    		'post_tags',
    		'post_id',
    		'tag_id'
    	);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
    	$post = new self;
    	$post->fill($fields);
    	$post->user_id = 1;
    	$post->save();

    	return $post;
    }

    public function edit($fields)
    {
    	$this->fill($fields);
    	$this->save();
    }

    public function remove()
    {
    	Storage::delete('uploads/' . $this->image);

    	$this->delete();
    }

    public function removeComments()
    {
        $this->comments->each(function($comment){
            $comment->remove();
        });

        return $this;
    }

    public function clearTagsRelations()
    {
        DB::table('post_tags')->where('post_id', $this->id)->delete();
        
        return $this;
    }

    public function uploadImage($image)
    {
    	if ($image == null) { return; }

    	if ($this->image != null) {
            Storage::delete('uploads/' . $this->image);
        }

    	$filename = Str::random(10) . '.' . $image->extension();
    	$image->storeAs('uploads', $filename);
    	$this->image = $filename;
    	$this->save();
    }

    public function getImage()
    {
    	if ($this->image == null) {
    		return '/img/default-50x50.gif';
    	}
    	
    	return '/uploads/' . $this->image;
    }

    public function setAuthor($id)
    {
        if ($id == null) { 
            $this->user_id = null; 
        }

        $this->user_id = $id;
        $this->save();
    }

    public function hasCategory()
    {
        return $this->category != null
            ? true
            : false;
    }

    public function setCategory($id)
    {
    	if ($id == null) { 
            $this->category_id = null; 
        }

    	$this->category_id = $id;
    	$this->save();
    }

    public function getCategoryTitle()
    {
        return $this->category != null 
            ? $this->category->title
            : 'no category';
    }

    public function getCategoryId()
    {
        return $this->category != null
            ? $this->category->id
            : null;
    }

    public function setTags($ids)
    {
    	if ($ids == null) { return; }

        // $this->tags()->attach($ids);
    	$this->tags()->sync($ids);
    }

    public function getTagsTitles()
    {
        return !$this->tags->isEmpty()
            ? implode(', ', $this->tags->pluck('title')->all())
            : 'no tags';
    }

    public function setDraft()
    {
    	$this->status = Post::IS_DRAFT;
    	$this->save();
    }

    public function setPublic()
    {
    	$this->status = Post::IS_PUBLIC;
    	$this->save();
    }

    public function toggleStatus($value)
    {
    	if ($value == null) {
    		return $this->setDraft();
    	}

    	return $this->setPublic();
    }

    public function setStandart()
    {
    	$this->is_featured = 0;
    	$this->save();
    }

    public function setFeatured()
    {
    	$this->is_featured = 1;
    	$this->save();
    }

    public function toggleFeatured($value)
    {
    	if ($value == null) {
    		return $this->setStandart();
    	}

    	return $this->setFeatured();
    }

    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');

        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $nextId = $this->hasNext(); 
        return self::find($nextId);
    }

    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $previousId = $this->hasPrevious();
        return self::find($previousId);
    }

    public function related()
    {
        return Post::where('category_id', $this->category_id)
            ->where('id', '<>', $this->id)
            ->get();
    }

    public static function getPopularPosts()
    {
        return self::orderBy('views', 'desc')->take(3)->get();
    }

    public static function getFeaturedPosts()
    {
        return self::where('is_featured', 1)->take(3)->get();
    }

    public static function getRecentPosts()
    {
        return self::orderBy('date', 'desc')->take(4)->get();
    }

    public function getComments()
    {
        return $this->comments()
                    ->DoesntHave('parent')
                    ->where('status', 1)
                    ->get();
    }

    public function getAuthorsName()
    {
        return $this->author != null
            ? $this->author->name 
            : 'anonymous author'; 
    }
}