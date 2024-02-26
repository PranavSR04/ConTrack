<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotifications extends Model
{
    use HasFactory;
    protected $fillable = [
        "log_id",
        "sendto_id",
        "status",
    ] ;
    public function activityLog()
    {
        return $this->belongsTo(ActivityLogs::class.'log_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
