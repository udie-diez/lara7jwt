<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Anggota extends Model
{
    protected $table = 'anggota';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'anggota' ;
    protected static $logOnlyDirty = true ;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }



}
