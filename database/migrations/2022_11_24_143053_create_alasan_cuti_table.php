<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlasanCutiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alasan_cuti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jenis_cuti_id');
            $table->foreign('jenis_cuti_id')
                ->references('id')->on('jenis_cuti')->onDelete('cascade');
            $table->string('alasan');
            $table->integer('max_hari');
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
        Schema::dropIfExists('alasan_cuti');
    }
}
