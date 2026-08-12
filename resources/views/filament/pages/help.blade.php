<x-filament-panels::page>
    {{-- Alert info --}}
    <div class="flex items-start gap-3 rounded-xl bg-primary-50 p-4 dark:bg-primary-500/10">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-500 text-white">
            <x-heroicon-o-information-circle class="h-5 w-5" />
        </div>
        <div>
            <p class="font-semibold text-gray-950 dark:text-white">
                Informasi
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Pelajari fitur Sistem Kehadiran Digital melalui panduan di bawah ini.
            </p>
        </div>
    </div>

    {{-- Grid card --}}
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @php
            $items = [
                ['number' => 1, 'title' => 'Presensi GPS', 'description' => 'Lakukan absensi masuk dan pulang menggunakan GPS.'],
                ['number' => 2, 'title' => 'Absen Manual', 'description' => 'Gunakan jika GPS atau internet mengalami kendala.'],
                ['number' => 3, 'title' => 'Pengajuan Izin', 'description' => 'Ajukan izin dengan memilih tanggal dan alasan.'],
                ['number' => 4, 'title' => 'Riwayat Kehadiran', 'description' => 'Lihat riwayat absensi yang telah dilakukan.'],
                ['number' => 5, 'title' => 'Status Pengajuan', 'description' => 'Pantau proses izin yang sedang diajukan.'],
                ['number' => 6, 'title' => 'Bantuan', 'description' => 'Hubungi admin jika mengalami kendala.'],
            ];
        @endphp

        @foreach ($items as $item)
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="mb-3 flex h-7 w-7 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-600 dark:bg-primary-500/20 dark:text-primary-400">
                    {{ $item['number'] }}
                </div>
                <h3 class="font-semibold text-gray-950 dark:text-white">
                    {{ $item['title'] }}
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $item['description'] }}
                </p>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>