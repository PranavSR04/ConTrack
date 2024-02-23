<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSAs extends Model
{
    use HasFactory;
    protected $table='msas';
    
    protected $fillable = [
        'msa_ref_id',
        'added_by',
        'client_name',
        'region',
        'start_date',
        'end_date',
        'comments',
        'is_active',
        'msa_doclink'
    ];
    
}
