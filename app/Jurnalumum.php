<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Jurnalumum extends Model
{
    protected $table = 'jurnalumum';

    use LogsActivity, Notifiable;
    protected static $logAttributes = ['*'];
    protected static $logName = 'jurnalumum';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
