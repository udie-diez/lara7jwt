<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Rekap_pajak extends Model
{
    protected $table = 'rekap_pajak';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'rekap_pajak';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
