<x-layouts.app>
    <x-slot name="header">
        Dashboard Resident Auditor
    </x-slot>

    <div>
        <!-- Stats Summary Section -->
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Kinerja & Scoring / Jadwal Berjalan -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 border border-slate-100 hover:border-slate-300 transition-colors">
                <dt>
                    <div class="absolute rounded-md bg-[#174b84] p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-slate-500">Audit Berjalan</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-slate-900">{{ $summary['jadwal_berjalan'] ?? 0 }}</p>
                    <div class="absolute inset-x-0 bottom-0 bg-slate-50 px-4 py-4 sm:px-6 border-t border-slate-100">
                        <div class="text-sm">
                            <a href="{{ route('jadwal-audit.index') }}" class="font-medium text-[#174b84] hover:text-[#123964]">Lihat detail<span class="sr-only"> Jadwal Audit</span></a>
                        </div>
                    </div>
                </dd>
            </div>

            <!-- Temuan Baru -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 border border-slate-100 hover:border-slate-300 transition-colors">
                <dt>
                    <div class="absolute rounded-md bg-red-500 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-slate-500">Temuan Baru</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-slate-900">{{ $summary['temuan_baru'] ?? 0 }}</p>
                    <div class="absolute inset-x-0 bottom-0 bg-slate-50 px-4 py-4 sm:px-6 border-t border-slate-100">
                        <div class="text-sm">
                            <a href="{{ route('temuan.index') }}" class="font-medium text-[#174b84] hover:text-[#123964]">Lihat temuan<span class="sr-only"> Temuan Baru</span></a>
                        </div>
                    </div>
                </dd>
            </div>

            <!-- KKA Menunggu Review -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 border border-slate-100 hover:border-slate-300 transition-colors">
                <dt>
                    <div class="absolute rounded-md bg-[#f4b41a] p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-slate-500">KKA Menunggu Review</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-slate-900">{{ $summary['kka_menunggu_review'] ?? 0 }}</p>
                    <div class="absolute inset-x-0 bottom-0 bg-slate-50 px-4 py-4 sm:px-6 border-t border-slate-100">
                        <div class="text-sm">
                            <a href="{{ route('kka.index') }}" class="font-medium text-[#174b84] hover:text-[#123964]">Proses sekarang<span class="sr-only"> KKA Review</span></a>
                        </div>
                    </div>
                </dd>
            </div>

            <!-- Tindak Lanjut Proses -->
            <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6 border border-slate-100 hover:border-slate-300 transition-colors">
                <dt>
                    <div class="absolute rounded-md bg-emerald-500 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                    <p class="ml-16 truncate text-sm font-medium text-slate-500">Tindak Lanjut Diproses</p>
                </dt>
                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                    <p class="text-2xl font-semibold text-slate-900">{{ $summary['tindak_lanjut_proses'] ?? 0 }}</p>
                    <div class="absolute inset-x-0 bottom-0 bg-slate-50 px-4 py-4 sm:px-6 border-t border-slate-100">
                        <div class="text-sm">
                            <a href="{{ route('tindak-lanjut.index') }}" class="font-medium text-[#174b84] hover:text-[#123964]">Pantau progres<span class="sr-only"> Tindak Lanjut</span></a>
                        </div>
                    </div>
                </dd>
            </div>
        </dl>

        <!-- Tables Section -->
        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
            
            <!-- Temuan Terbaru -->
            <div class="rounded-lg bg-white shadow border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-200 bg-white px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-base font-semibold leading-6 text-slate-900">Temuan Terbaru</h3>
                    <a href="{{ route('temuan.index') }}" class="text-sm font-medium text-[#174b84] hover:text-[#123964]">Lihat Semua</a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-slate-200">
                        @forelse ($temuanTerbaru ?? [] as $temuan)
                            <li class="p-4 sm:px-6 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="truncate">
                                        <div class="flex text-sm">
                                            <p class="font-medium text-[#174b84] truncate">{{ $temuan->kka->jadwalAudit->cabang->nama_cabang ?? 'Cabang Unknown' }}</p>
                                            <p class="ml-1 shrink-0 font-normal text-slate-500"> - {{ Str::title(str_replace('_', ' ', $temuan->jenis_temuan)) }}</p>
                                        </div>
                                        <div class="mt-2 flex">
                                            <div class="flex items-center text-sm text-slate-500">
                                                <p class="truncate">{{ Str::limit($temuan->deskripsi, 50) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 shrink-0 flex flex-col items-end">
                                        @php
                                            $statusColors = [
                                                'baru' => 'bg-red-100 text-red-700',
                                                'berulang' => 'bg-orange-100 text-orange-700',
                                                'dalam_proses' => 'bg-yellow-100 text-yellow-800',
                                                'selesai' => 'bg-green-100 text-green-700',
                                            ];
                                            $colorClass = $statusColors[$temuan->status] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colorClass }}">
                                            {{ Str::title(str_replace('_', ' ', $temuan->status)) }}
                                        </span>
                                        <span class="text-xs text-slate-400 mt-2">{{ $temuan->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 sm:px-6 text-sm text-slate-500 text-center py-8">
                                Belum ada temuan terbaru.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Jadwal Mendatang -->
            <div class="rounded-lg bg-white shadow border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-200 bg-white px-4 py-5 sm:px-6 flex justify-between items-center">
                    <h3 class="text-base font-semibold leading-6 text-slate-900">Jadwal Audit Mendatang</h3>
                    <a href="{{ route('jadwal-audit.index') }}" class="text-sm font-medium text-[#174b84] hover:text-[#123964]">Lihat Semua</a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-slate-200">
                        @forelse ($jadwalMendatang ?? [] as $jadwal)
                            <li class="p-4 sm:px-6 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="truncate">
                                        <div class="flex text-sm">
                                            <p class="font-medium text-slate-900 truncate">{{ $jadwal->cabang->nama_cabang ?? 'Cabang' }}</p>
                                        </div>
                                        <div class="mt-2 flex gap-4">
                                            <div class="flex items-center text-sm text-slate-500">
                                                <svg class="mr-1.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6.75h1.5m-1.5 3h1.5m-1.5 3h1.5M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                                </svg>
                                                <p class="truncate">{{ $jadwal->bidang->nama_bidang ?? 'Semua Bidang' }}</p>
                                            </div>
                                            <div class="flex items-center text-sm text-slate-500">
                                                <svg class="mr-1.5 h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                </svg>
                                                <p class="truncate">{{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 shrink-0">
                                        @php
                                            $jadwalStatusColors = [
                                                'terjadwal' => 'bg-blue-100 text-blue-700',
                                                'berlangsung' => 'bg-yellow-100 text-yellow-800',
                                                'selesai' => 'bg-green-100 text-green-700',
                                                'batal' => 'bg-red-100 text-red-700',
                                            ];
                                            $jadwalColor = $jadwalStatusColors[$jadwal->status] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $jadwalColor }} ring-1 ring-inset ring-slate-500/10">
                                            {{ Str::title($jadwal->status) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 sm:px-6 text-sm text-slate-500 text-center py-8">
                                Belum ada jadwal audit mendatang.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</x-layouts.app>
