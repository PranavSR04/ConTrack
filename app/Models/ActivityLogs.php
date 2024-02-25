<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        "contract_id",
        "msa_id",
        "performed_by",
        "action",
    ] ;
    public function notifications()
    {
        return $this->hasMany(UserNotifications::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    protected static function boot()
{
    parent::boot();

    static::created(function ($activityLogs) {
        $users = User::all();
        
        foreach ($users as $user) {
            $notification = new UserNotifications([
                'log_id' => $activityLogs->id,
                'sendto_id' => $user->id,
            ]);
            $notification->save();
        }
    });
    
}


public function msa()
{
    return $this->belongsTo(MSAs::class, 'msa_id');
}

public function contract()
{
    return $this->belongsTo(Contracts::class, 'contract_id');
}
}
