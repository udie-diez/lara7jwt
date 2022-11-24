<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jenis_cuti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alasan', 'potong_cuti_tahunan', 'status',
    ];
}
