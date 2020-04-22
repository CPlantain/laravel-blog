<?php

namespace App;

use App\Exceptions\User as Exc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, UserStatus, UserAuth;

    const IS_ACTIVE = 0;
    const IS_BANNED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('uploads/' . $this->avatar);
        $this->clearRelatedPosts()
            ->removeRelatedComments()
            ->delete();
    }

    public function clearRelatedPosts()
    {
        $this->posts->each(function($post){
            $post->setAuthor(null);
        });

        return $this;
    }

    public function removeRelatedComments()
    {
        $this->comments->each(function($comment){
            $comment->remove();
        });

        return $this;
    }

    public function uploadAvatar($image)
    {
        if ($image == null) { return; }

        if ($this->avatar != null) {
            Storage::delete('uploads/' . $this->avatar);
        }

        $filename = $image->hashName();
        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();
        return $filename;
    }

    public function getAvatar()
    {
        if ($this->avatar == null) {
            return '/img/default-50x50.gif';
        }
        
        return '/uploads/' . $this->avatar;
    }

    public function makeNormal()
    {
        $this->is_admin = 0;
        $this->save();
    }

    public function makeAdmin()
    {
        $this->is_admin = 1;
        $this->save();
    }

    public function toggleAdmin()
    {
        if ($this->is_admin == 1) {
            return $this->makeNormal();
        }

        return $this->makeAdmin();
    }   

    public function getRole()
    {
        return $this->is_admin == 1 ? 'Admin' : 'Regular User'; 
    }

    public function isAdmin()
    {
        return $this->is_admin == 1 ? true : false; 
    }
}
