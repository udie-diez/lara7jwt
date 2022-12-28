<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Register extends Model
{
    protected $table = 'register';
    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'register';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
