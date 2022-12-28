<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Panjar extends Model
{
    protected $table = 'panjar';
    protected $guarded = [];


    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'panjar';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
