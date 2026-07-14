<?php

namespace App\Jobs;

use App\Models\Kka;
use App\Models\Scoring;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HitungScoringJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(
        public Kka $kka
    ) {
    }

    public function handle(): void
    {
        $this->kka->loadMissing('jawaban');

        $totalSkor = $this->kka->jawaban->sum('nilai');

        Scoring::updateOrCreate(
            ['kka_id' => $this->kka->id],
            [
                'total_skor'      => $totalSkor,
                'kategori_risiko' => $this->tentukanKategoriRisiko($totalSkor),
                'dihitung_pada'   => now(),
            ]
        );
    }

    private function tentukanKategoriRisiko(float $totalSkor): string
    {
        return match (true) {
            $totalSkor >= 85 => 'low',
            $totalSkor >= 65 => 'medium',
            default          => 'high',
        };
    }
}
