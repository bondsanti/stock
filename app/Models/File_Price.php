<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File_Price extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'file_price';

    protected $fillable = ['project_id', 'exp', 'remark', 'created_by', 'email_alert', 'file'];

    protected $casts = [
        'email_alert' => 'array',
    ];
}
