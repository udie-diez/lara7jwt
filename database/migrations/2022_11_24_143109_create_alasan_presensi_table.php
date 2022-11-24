<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlasanPresensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alasan_presensi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('alasan');
            $table->enum('status', ['aktif', 'tidak'])->default('aktif');
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
        Schema::dropIfExists('alasan_presensi');
    }
}
