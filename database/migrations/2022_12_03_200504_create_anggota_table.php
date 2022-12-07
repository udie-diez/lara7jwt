<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('no_anggota', 20);
            $table->string('nama', 100);
            $table->string('nik', 20);
            $table->string('phone_number', 15)->nullable();
            $table->string('email', 15)->nullable();
            $table->string('lokasi_kerja', 255)->nullable();
            $table->string('jabatan', 255)->nullable();
            $table->enum('status', ['aktif', 'tidak', 'keluar'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota');
    }
}
