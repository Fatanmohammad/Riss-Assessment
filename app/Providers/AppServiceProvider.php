<?php

namespace App\Providers;

use App\Models\Cabang;
use App\Models\JadwalAudit;
use App\Models\Kka;
use App\Models\Laporan;
use App\Models\Temuan;
use App\Models\TindakLanjut;
use App\Policies\CabangPolicy;
use App\Policies\JadwalAuditPolicy;
use App\Policies\KkaPolicy;
use App\Policies\LaporanPolicy;
use App\Policies\TemuanPolicy;
use App\Policies\TindakLanjutPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Cabang::class, CabangPolicy::class);
        Gate::policy(JadwalAudit::class, JadwalAuditPolicy::class);
        Gate::policy(Kka::class, KkaPolicy::class);
        Gate::policy(Laporan::class, LaporanPolicy::class);
        Gate::policy(Temuan::class, TemuanPolicy::class);
        Gate::policy(TindakLanjut::class, TindakLanjutPolicy::class);
    }
}
