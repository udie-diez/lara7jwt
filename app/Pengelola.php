<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Pengelola extends Model
{
    protected $table = 'pengelola';
    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'pengelola';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
