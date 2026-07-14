<?php

namespace App\Jobs;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLaporanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public Laporan $laporan
    ) {
    }

    public function handle(): void
    {
        $this->laporan->loadMissing('jadwalAudit.kkas.temuan', 'jadwalAudit.cabang', 'jadwalAudit.bidang');

        // Catatan: proses penyusunan file PDF/DOCX sebenarnya akan
        // didelegasikan ke App\Services\LaporanGeneratorService
        // (belum dibuat sesuai permintaan). Job ini menyiapkan alurnya:
        //
        // $filePath = app(\App\Services\LaporanGeneratorService::class)
        //     ->generate($this->laporan);
        //
        // $this->laporan->update([
        //     'file_path' => $filePath,
        //     'status'    => 'final',
        // ]);
    }

    public function failed(\Throwable $exception): void
    {
        // Bisa dicatat ke log / notifikasi ke pembuat laporan bahwa generate gagal.
        logger()->error('Gagal generate laporan #' . $this->laporan->id . ': ' . $exception->getMessage());
    }
}
