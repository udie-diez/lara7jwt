<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlasanPresensi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alasan_presensi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alasan', 'status',
    ];
}
