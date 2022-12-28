<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Saldoawal extends Model
{
    protected $table = 'saldoawal';
    protected $guarded = [];
    
    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'saldoawal';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
