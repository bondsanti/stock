<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;
    protected $connection = 'mysql_report';
	protected $table = 'rental_room';
	public $timestamps = false;

	public function room()
	{
		return $this->belongsTo(Room::class);
	}


}
