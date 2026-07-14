<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,        // 1. master role (RA, Checker, Admin SKAI)
            BidangSeeder::class,      // 2. master bidang (Teller, Kredit, dst)
            CabangSeeder::class,      // 3. struktur cabang induk & anak cabang
            KkaPertanyaanSeeder::class, // 4. pertanyaan KKA per bidang (butuh Bidang sudah ada)
            UserSeeder::class,        // 5. user dummy (butuh Role & Cabang sudah ada)
        ]);
    }
}