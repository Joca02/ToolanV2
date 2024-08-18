<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
    'username', 'password', 'email', 'name', 'prof_description', 'profile_picture', 'user_type', 'gender', 'verified', 'verification_token'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'id_user');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'id_user');
    }

    public function followers()
    {
        return $this->hasMany(Following::class, 'id_followed_user');
    }

    public function following()
    {
        return $this->hasMany(Following::class, 'id_follower');
    }

    public function bans()
    {
         return $this->hasOne(Ban::class, 'id_user');
    }

    public function deactivation()
    {
         return $this->hasOne(DeactivatedUser::class, 'id_user');
    }
}

