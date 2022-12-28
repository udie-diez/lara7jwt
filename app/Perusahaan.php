<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    use LogsActivity, Notifiable;
    protected static $logAttributes = ['*'];
    protected static $logName = 'perusahaan';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }

}
