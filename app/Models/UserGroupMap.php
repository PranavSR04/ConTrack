<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupMap extends Model
{
    use HasFactory;

    protected $table = 'user_group_map'; 

    protected $fillable=[
        "group_id",
        "user_id"
    ];
}
