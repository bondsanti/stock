<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'plan_id', 'floor','room_address', 'address','status_id', 'price', 'area','building','direction','fixseller','special_price1','special_price2','special_price3',

    ];
    public function furnitures()
	{
		return $this->belongsToMany(Furniture::class);
	}

	public function facilities()
	{
		return $this->belongsToMany(Facility::class);
	}

	public function project()
	{
		return $this->belongsTo(Project::class,'project_id');
	}

	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function status()
	{
		return $this->belongsTo(Status_Room::class);
	}

	public function bank()
	{
		return $this->belongsTo(Bank::class);
	}

    public function booking()
    {
        return $this->hasOne(Booking::class, 'rooms_id', 'id');
    }


}
