<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Pengurus extends Model
{
    protected $table = 'pengurus';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['nama','nik','jk','alamat','phone','email','status','jabatan'];
    protected static $logName = 'pengurus';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }


}
