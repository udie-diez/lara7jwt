<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Setoran extends Model
{
    protected $table = 'setoran';
    protected $guarded = [];

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'setoran';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
