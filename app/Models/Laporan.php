<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'jenis',            // bulanan, triwulan
        'periode',
        'cabang_id',
        'ra_id',
        'file_path',
        'dibuat_oleh',
    ];

    protected $casts = [];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function ra()
    {
        return $this->belongsTo(User::class, 'ra_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
