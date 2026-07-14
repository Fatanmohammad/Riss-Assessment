<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'tindak_lanjuts';

    protected $fillable = [
        'flag_id',
        'checker_id',
        'keterangan',
        'bukti_fisik',
        'status',     // proses, selesai
    ];

    public function flag()
    {
        return $this->belongsTo(FlagKejanggalan::class, 'flag_id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }
}
