<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class KkaPertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        $pertanyaanPerBidang = [
            'TELLER' => [
                ['pertanyaan' => 'Apakah kas fisik sesuai dengan sistem pada akhir hari?', 'bobot_nilai' => 5],
                ['pertanyaan' => 'Apakah dual control saat pembukaan brankas dijalankan?', 'bobot_nilai' => 4],
                ['pertanyaan' => 'Apakah selisih kas dilaporkan sesuai prosedur?', 'bobot_nilai' => 3],
            ],
            'KREDIT' => [
                ['pertanyaan' => 'Apakah dokumen agunan lengkap sebelum pencairan kredit?', 'bobot_nilai' => 5],
                ['pertanyaan' => 'Apakah analisa kelayakan kredit sesuai SOP?', 'bobot_nilai' => 5],
                ['pertanyaan' => 'Apakah monitoring kredit bermasalah dilakukan rutin?', 'bobot_nilai' => 4],
            ],
            'APU_PPT' => [
                ['pertanyaan' => 'Apakah CDD/EDD dilakukan untuk nasabah berisiko tinggi?', 'bobot_nilai' => 5],
                ['pertanyaan' => 'Apakah transaksi mencurigakan dilaporkan tepat waktu?', 'bobot_nilai' => 5],
            ],
            'BIAYA' => [
                ['pertanyaan' => 'Apakah bukti pengeluaran biaya lengkap dan terotorisasi?', 'bobot_nilai' => 3],
                ['pertanyaan' => 'Apakah realisasi biaya sesuai anggaran yang disetujui?', 'bobot_nilai' => 3],
            ],
            'TEKNOLOGI' => [
                ['pertanyaan' => 'Apakah akses sistem sesuai hak akses jabatan?', 'bobot_nilai' => 4],
                ['pertanyaan' => 'Apakah backup data dilakukan sesuai jadwal?', 'bobot_nilai' => 4],
            ],
        ];

        foreach ($pertanyaanPerBidang as $kodeBidang => $daftarPertanyaan) {
            $bidang = Bidang::where('kode_bidang', $kodeBidang)->first();

            if (! $bidang) {
                continue; // pastikan BidangSeeder dijalankan lebih dulu
            }

            foreach ($daftarPertanyaan as $urutan => $item) {
                $bidang->pertanyaanKka()->updateOrCreate(
                    ['pertanyaan' => $item['pertanyaan']],
                    [
                        'bobot_nilai' => $item['bobot_nilai'],
                        'urutan' => $urutan + 1,
                        'wajib_diisi' => true,
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}