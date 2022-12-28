<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Akses extends Model
{
    protected $table = 'akses';
    protected $guarded = [];


    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['userid','modul','lihat','cud','cetak'];
    protected static $logName = 'akses' ;
    protected static $logOnlyDirty = true ;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
     

}
