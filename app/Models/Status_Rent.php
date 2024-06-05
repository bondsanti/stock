<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status_Rent extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'status_rent';
}
