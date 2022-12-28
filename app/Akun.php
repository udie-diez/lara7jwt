<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Akun extends Model
{
    protected $table = 'akun';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'akun';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
