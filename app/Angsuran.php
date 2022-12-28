<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Angsuran extends Model
{
    protected $table = 'angsuran';
    
    use LogsActivity, Notifiable;
    protected static $logAttributes = ['*'];
    protected static $logName = 'angsuran';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
