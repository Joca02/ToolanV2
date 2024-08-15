<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeactivatedUser extends Model
{
    use HasFactory;

    protected $table = 'deactivated_users';
    protected $primaryKey = 'id_deactivation';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 'token', 'time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
