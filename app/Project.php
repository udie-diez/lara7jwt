<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class Project extends Model
{
    //
    protected $table = 'project';
    protected $guarded = [];


    use LogsActivity, Notifiable ;
    protected static $logAttributes = ['nama','perusahaanid','nilai','pemesanid','pic','status','no_spk','tgl_spk','paket','keuntungan','lamapekerjaan'];
    protected static $logName = 'project';
    protected static $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return " {$eventName} ";
    }

}
