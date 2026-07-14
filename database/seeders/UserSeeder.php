<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::where('kode_role', 'ADMIN_SKAI')->first();
        $roleChecker = Role::where('kode_role', 'CHECKER')->first();
        $roleRa = Role::where('kode_role', 'RA')->first();

        // 1 akun admin SKAI (tidak terikat cabang tertentu)
        User::updateOrCreate(
            ['email' => 'admin.skai@banksulteng.co.id'],
            [
                'name' => 'Admin SKAI',
                'password' => Hash::make('password'),
                'role_id' => $roleAdmin?->id,
                'cabang_id' => null,
            ]
        );

        // 1 akun checker/supervisor
        User::updateOrCreate(
            ['email' => 'checker.skai@banksulteng.co.id'],
            [
                'name' => 'Checker SKAI',
                'password' => Hash::make('password'),
                'role_id' => $roleChecker?->id,
                'cabang_id' => null,
            ]
        );

        // 1 akun RA dummy untuk tiap cabang induk (RA cabang mengisi data untuk anak cabangnya)
        $cabangIndukList = Cabang::where('tipe', 'induk')->get();

        foreach ($cabangIndukList as $cabang) {
            $emailSlug = strtolower(str_replace(' ', '', $cabang->kode_cabang));

            User::updateOrCreate(
                ['email' => "ra.{$emailSlug}@banksulteng.co.id"],
                [
                    'name' => "RA {$cabang->nama_cabang}",
                    'password' => Hash::make('password'),
                    'role_id' => $roleRa?->id,
                    'cabang_id' => $cabang->id,
                ]
            );
        }
    }
}