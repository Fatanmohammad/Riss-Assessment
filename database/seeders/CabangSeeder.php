<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        // Struktur: setiap cabang induk punya beberapa anak cabang.
        // Sesuaikan nama & kode dengan jaringan cabang PT Bank Sulteng yang sebenarnya.
        $jaringan = [
            [
                'kode_cabang' => 'PALU',
                'nama_cabang' => 'Cabang Palu',
                'alamat' => 'Jl. Sam Ratulangi, Palu',
                'anak' => [
                    ['kode_cabang' => 'PALU-01', 'nama_cabang' => 'Capem Palu Selatan'],
                    ['kode_cabang' => 'PALU-02', 'nama_cabang' => 'Capem Palu Timur'],
                ],
            ],
            [
                'kode_cabang' => 'PARIGI',
                'nama_cabang' => 'Cabang Parigi',
                'alamat' => 'Jl. Trans Sulawesi, Parigi',
                'anak' => [
                    ['kode_cabang' => 'PARIGI-01', 'nama_cabang' => 'Capem Parigi Utara'],
                ],
            ],
            [
                'kode_cabang' => 'LUWUK',
                'nama_cabang' => 'Cabang Luwuk',
                'alamat' => 'Jl. Yos Sudarso, Luwuk',
                'anak' => [
                    ['kode_cabang' => 'LUWUK-01', 'nama_cabang' => 'Capem Luwuk Banggai'],
                ],
            ],
            [
                'kode_cabang' => 'POSO',
                'nama_cabang' => 'Cabang Poso',
                'alamat' => 'Jl. Pulau Sumatera, Poso',
                'anak' => [
                    ['kode_cabang' => 'POSO-01', 'nama_cabang' => 'Capem Poso Pesisir'],
                ],
            ],
            [
                'kode_cabang' => 'BUOL',
                'nama_cabang' => 'Cabang Buol',
                'alamat' => 'Jl. Prof. Moh. Yamin, Buol',
                'anak' => [],
            ],
            [
                'kode_cabang' => 'TOLITOLI',
                'nama_cabang' => 'Cabang Tolitoli',
                'alamat' => 'Jl. Magamu, Tolitoli',
                'anak' => [],
            ],
        ];

        foreach ($jaringan as $data) {
            $induk = Cabang::updateOrCreate(
                ['kode_cabang' => $data['kode_cabang']],
                [
                    'nama_cabang' => $data['nama_cabang'],
                    'tipe' => 'induk',
                    'parent_id' => null,
                    'alamat' => $data['alamat'],
                    'aktif' => true,
                ]
            );

            foreach ($data['anak'] as $anak) {
                Cabang::updateOrCreate(
                    ['kode_cabang' => $anak['kode_cabang']],
                    [
                        'nama_cabang' => $anak['nama_cabang'],
                        'tipe' => 'anak',
                        'parent_id' => $induk->id,
                        'alamat' => $data['alamat'],
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}