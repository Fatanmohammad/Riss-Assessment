<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['kode_role' => 'RA', 'nama_role' => 'Resident Auditor'],
            ['kode_role' => 'CHECKER', 'nama_role' => 'Checker / Supervisor'],
            ['kode_role' => 'ADMIN_SKAI', 'nama_role' => 'Admin SKAI'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['kode_role' => $role['kode_role']],
                array_merge($role, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}