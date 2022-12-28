<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['akunid','debit','item','itemid','kredit','tanggal'];
    protected static $logName = 'transaksi';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
    

}
