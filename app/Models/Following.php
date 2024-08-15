<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;

    protected $table = 'following';
    protected $primaryKey = 'id_follow';
    public $timestamps = false;

    protected $fillable = [
        'id_followed_user', 'id_follower', 'time'
    ];

    public function followedUser()
    {
        return $this->belongsTo(User::class, 'id_followed_user');
    }

    public function follower()
    {
        return $this->belongsTo(User::class, 'id_follower');
    }
}
