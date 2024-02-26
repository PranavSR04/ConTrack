<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociatedUsers extends Model
{
    use HasFactory;
    protected $table = 'associated_users';
    protected $fillable = [
        'contract_id',
        'user_id',
    ];

}
