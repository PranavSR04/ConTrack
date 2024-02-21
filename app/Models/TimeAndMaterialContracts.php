<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeAndMaterialContracts extends Model
{
    use HasFactory;
    protected $table='tm_contracts';
    protected $fillable = [
        'contract_id',
        'milestone_desc',
        'milestone_enddate',
        'amount',
    ];
   
}
