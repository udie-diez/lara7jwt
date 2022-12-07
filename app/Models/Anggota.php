<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anggota';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_anggota', 'nama', 'nik', 'phone_number',
        'email', 'lokasi_kerja', 'jabatan', 'status',
    ];
}
