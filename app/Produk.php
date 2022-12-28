<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Produk extends Model
{
    protected $table = 'produk';

    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['nama','satuan'];
    protected static $logName = 'produk';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }
}
