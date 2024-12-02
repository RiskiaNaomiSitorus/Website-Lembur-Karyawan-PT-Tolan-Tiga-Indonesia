<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLemburTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembur', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_lengkap'); // Nama Lengkap
            $table->string('id_karyawan'); // ID Karyawan
            $table->date('tanggal_lembur'); // Tanggal Lembur
            $table->time('jam_masuk'); // Jam Masuk
            $table->time('jam_keluar'); // Jam Keluar
            $table->string('jenis_lembur'); // Jenis Lembur
            $table->decimal('gaji', 15, 2); // Gaji
            $table->decimal('jam_kerja_lembur', 5, 2)->nullable(); // Total Waktu Kerja
            $table->decimal('jam_i', 5, 2)->nullable(); // Jam I
            $table->decimal('jam_ii', 5, 2)->nullable(); // Jam II
            $table->decimal('jam_iii', 5, 2)->nullable(); // Jam III
            $table->decimal('jam_iv', 5, 2)->nullable(); // Jam IV
            $table->decimal('total_jam_lembur', 5, 2)->nullable(); // Total Jam Lembur
            $table->decimal('upah_lembur', 15, 2)->nullable(); // Upah Lembur
            $table->text('keterangan')->nullable(); // Keterangan
            $table->timestamps(); // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lembur');
    }
}
