<?php

namespace App;

use Auth;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\BannedUserCannotCommentException;

class Comment extends Model
{

    public function post()
    {
    	return $this->belongsTo(Post::class);
    }

    public function author()
    {	
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function subComments()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public static function createNew(array $data)
    {
        if(Auth::user()->isBanned()){
            throw new BannedUserCannotCommentException;
        }

        $comment = new self;
        $comment->text = $data['text'];
        $comment->post_id = $data['post_id'];
        $comment->parent_id = $data['parent_id'];
        $comment->user_id = Auth::user()->id;

        $comment->save();

        return $comment;
    }

    public static function getAll()
    {
        return self::doesntHave('parent', function(Builder $query){
            $query->where('status', 1);
        })->get();
    }

    public function allow()
    {
        $this->status = 1;
        $this->save();
    }

    public function disallow()
    {
        $this->status = 0;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status == 0) {
            return $this->allow();
        }

        return $this->disallow();
    }

    public function remove()
    {
        $this->delete();
    }

    public static function countNew()
    {
        return self::where('status', 0)->count();
    }
}
