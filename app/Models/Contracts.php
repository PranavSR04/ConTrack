<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_ref_id',
        'msa_ref_id',
        'contract_added_by',
        'contract_type',
        'date_of_signature',
        'comments',
        'start_date',
        'end_date',
        'du',
        'estimated_amount',
        'contract_doclink',
        'is_active',
    ];
}
