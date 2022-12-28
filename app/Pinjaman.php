<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';
    protected $guarded = [];


    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['*'];
    protected static $logName = 'simpanan';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }

}
