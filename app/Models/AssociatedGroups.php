<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociatedGroups extends Model
{
    use HasFactory;
     protected $table = 'associated_groups';
    protected $fillable = [
        'contract_id',
        'group_id',
    ];
}
