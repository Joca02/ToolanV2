<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    protected $table = 'bans';
    protected $primaryKey = 'id_ban';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 'date_start', 'date_end', 'ban_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
