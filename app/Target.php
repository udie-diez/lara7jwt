<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Target extends Model
{
    protected $table = 'target';
    protected $guarded = [];

    
    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'target';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
