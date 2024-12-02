<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id(); // ID auto-increment
            $table->string('id_karyawan')->unique(); // ID Karyawan
            $table->string('nama_karyawan'); // Nama Karyawan
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']); // Jenis Kelamin
            $table->string('jabatan'); // Jabatan
            $table->enum('status', ['Aktif', 'Tidak Aktif']); // Status
            $table->decimal('gaji', 15, 2); // Gaji
            $table->timestamps(); // Created at dan Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
}
