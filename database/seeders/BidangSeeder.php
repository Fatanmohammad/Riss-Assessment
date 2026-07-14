<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidangs = [
            ['kode_bidang' => 'TELLER', 'nama_bidang' => 'Teller', 'deskripsi' => 'Audit layanan kas & teller'],
            ['kode_bidang' => 'KREDIT', 'nama_bidang' => 'Kredit', 'deskripsi' => 'Audit proses & administrasi kredit'],
            ['kode_bidang' => 'APU_PPT', 'nama_bidang' => 'APU-PPT', 'deskripsi' => 'Anti Pencucian Uang & Pencegahan Pendanaan Terorisme'],
            ['kode_bidang' => 'BIAYA', 'nama_bidang' => 'Biaya', 'deskripsi' => 'Audit pengelolaan biaya operasional'],
            ['kode_bidang' => 'TEKNOLOGI', 'nama_bidang' => 'Teknologi/TI', 'deskripsi' => 'Audit keamanan & operasional sistem TI'],
        ];

        foreach ($bidangs as $bidang) {
            DB::table('bidangs')->updateOrInsert(
                ['kode_bidang' => $bidang['kode_bidang']],
                array_merge($bidang, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}