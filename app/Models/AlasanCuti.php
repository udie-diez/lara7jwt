<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlasanCuti extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alasan_cuti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jenis_cuti_id', 'alasan', 'max_hari', 'status',
    ];

    public function jenis_cuti()
    {
        return $this->belongsTo('App\Models\JenisCuti', 'jenis_cuti_id', 'id');
    }
}
