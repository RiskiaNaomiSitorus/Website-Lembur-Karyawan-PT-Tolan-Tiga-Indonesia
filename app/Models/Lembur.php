<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'lembur';

    // The attributes that are mass assignable.
    protected $fillable = [
        'nama_lengkap',
        'id_karyawan',
        'tanggal_lembur',
        'jam_masuk',
        'jam_keluar',
        'jenis_lembur',
        'gaji',
        'jam_kerja_lembur',
        'jam_i',
        'jam_ii',
        'jam_iii',
        'jam_iv',
        'total_jam_lembur',
        'upah_lembur',
        'keterangan',
    ];

    // The attributes that should be cast to native types.
    protected $casts = [
        'tanggal_lembur' => 'date',
        'jam_masuk' => 'datetime:H:i', // Time format
        'jam_keluar' => 'datetime:H:i', // Time format
        'gaji' => 'decimal:2',
        'jam_kerja_lembur' => 'decimal:2',
        'jam_i' => 'decimal:2',
        'jam_ii' => 'decimal:2',
        'jam_iii' => 'decimal:2',
        'jam_iv' => 'decimal:2',
        'total_jam_lembur' => 'decimal:2',
        'upah_lembur' => 'decimal:2',
    ];

    // The attributes that should be mutated to dates.
    protected $dates = [
        'tanggal_lembur',
        'created_at',
        'updated_at',
    ];
}
