<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'role_user';

    public function user_ref()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    public function role()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(Role::class, 'role_id','id');
    }
}
