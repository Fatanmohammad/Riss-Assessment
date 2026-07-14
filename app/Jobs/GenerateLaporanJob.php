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
        $this->laporan->loadMissing('cabang', 'ra', 'pembuat');

        // Proses generate file PDF/DOCX akan didelegasikan ke
        // App\Services\LaporanGeneratorService:
        //
        // $filePath = app(\App\Services\LaporanGeneratorService::class)
        //     ->generate($this->laporan);
        //
        // $this->laporan->update(['file_path' => $filePath]);
    }

    public function failed(\Throwable $exception): void
    {
        // Bisa dicatat ke log / notifikasi ke pembuat laporan bahwa generate gagal.
        logger()->error('Gagal generate laporan #' . $this->laporan->id . ': ' . $exception->getMessage());
    }
}
