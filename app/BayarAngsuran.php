<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class BayarAngsuran extends Model
{
    protected $table = 'bayar_angsuran';
    protected $guarded = [];

    use LogsActivity, Notifiable;
    protected static $logAttributes = ['*'];
    protected static $logName = 'bayar_angsuran';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
