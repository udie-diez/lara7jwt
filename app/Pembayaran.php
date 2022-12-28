<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'pembayaran';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
