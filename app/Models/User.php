<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'users';

    public function role_dept()
    {

        return $this->belongsTo(Role_user::class,'id','user_id');
    }

    public function role_position()
	{
		return $this->hasOne(Role::class, 'id','position_id');
	}

    public function role()
    {
        return $this->hasOne(Role_user::class, 'id', 'user_id');
    }


}
