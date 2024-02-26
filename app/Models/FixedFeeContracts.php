<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedFeeContracts extends Model
{
    use HasFactory;
    protected $table='ff_contracts';
    protected $fillable = [
        'contract_id',
        'milestone_desc',
        'milestone_enddate',
        'percentage',
        'amount',
    ];
}
