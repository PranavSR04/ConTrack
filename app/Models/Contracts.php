<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_ref_id',
        'msa_id',
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
    // public function tmContract()
    // {
    //     return $this->hasMany(TimeAndMaterialContracts::class, 'contract_id');
    // }

    // public function ffContract()
    // {
    //     return $this->hasMany(FixedFeeContracts::class, 'contract_id');
    // }
    // public function userList()
    // {
    //     return $this->belongsTo(User::class, 'added_by'); 
    // }

    // public function msaList()
    // {
    //     return $this->belongsTo(MSAs::class, 'msa_id'); 
    // }

}
