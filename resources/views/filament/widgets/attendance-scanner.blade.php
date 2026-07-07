<x-filament-widgets::widget>
    <x-filament::section>
        <div
            x-data="{
                sending: false,
                init() {
                    this.tick();
                    setInterval(() => this.tick(), 1000);
                },
                tick() {
                    const now = new Date();
                    const pad = (n) => String(n).padStart(2, '0');
                    this.$refs.clock.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                },
                initiateGeolookup() {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung deteksi lokasi.');
                        return;
                    }
                    this.sending = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            $wire.call('processAttendance', position.coords.latitude, position.coords.longitude)
                                .then(() => { this.sending = false; })
                                .catch(() => { this.sending = false; });
                        },
                        (error) => {
                            this.sending = false;
                            // ...your existing error switch block, e.g.:
                            // if (error.code === error.PERMISSION_DENIED) alert('Izin lokasi ditolak.');
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                }
            }"
            class="flex flex-col items-center justify-center gap-6 p-4"
        >
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-500/10 text-amber-500 rounded-xl">
                    <x-heroicon-o-clock class="w-8 h-8 animate-pulse" />
                </div>
                <div wire:ignore class="flex flex-col">
                    <span class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Sistem Kehadiran Digital</span>
                    <span x-ref="clock" class="text-3xl font-black text-gray-900 dark:text-white font-mono tracking-tight">
                        {{ now()->isoFormat('HH:mm:ss') }}
                    </span>
                    <span class="text-xs text-gray-400">
                        {{ now()->isoFormat('D MMMM YYYY') }} • Cabang: {{ auth()->user()->employeeProfile?->branch?->branch_name ?? 'Belum Diatur' }}
                    </span>
                </div>
            </div>

            <div class="w-full flex flex-col md:flex-row items-center justify-between gap-3">

                {{-- Attendance state button --}}
                @if(!$alreadyCheckedIn && !$alreadyCheckedOut)
                    <button
                        type="button"
                        x-on:click="initiateGeolookup"
                        x-bind:disabled="sending"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 font-semibold text-white bg-green-500 hover:bg-green-600 transition-all rounded-xl shadow-md focus:ring-4 focus:ring-green-500/20 disabled:opacity-60"
                    >
                        <x-heroicon-m-finger-print class="w-5 h-5" />
                        <span x-text="sending ? 'Mengunci Sinyal Satelit...' : 'Kirim Kehadiran (GPS)'"></span>
                    </button>
                @elseif($alreadyCheckedIn && !$alreadyCheckedOut)
                    <button
                        type="button"
                        x-on:click="initiateGeolookup"
                        x-bind:disabled="sending"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 font-semibold text-white bg-red-500 hover:bg-red-600 transition-all rounded-xl shadow-md focus:ring-4 focus:ring-red-500/20 disabled:opacity-60"
                    >
                        <x-heroicon-m-finger-print class="w-5 h-5" />
                        <span x-text="sending ? 'Mengunci Sinyal Satelit...' : 'Absen Pulang (GPS)'"></span>
                    </button>
                @else
                    <div class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 font-semibold text-green-600 bg-green-50 dark:bg-green-950/30 rounded-xl border border-green-200">
                        <x-heroicon-m-check-circle class="w-5 h-5" />
                        <span>Anda Sudah Absen Hari Ini</span>
                    </div>
                @endif

                {{-- Action buttons --}}
                <div class="w-full md:w-auto flex flex-row justify-between md:justify-center items-center gap-3">
                    {{ $this->leaveRequestAction }}
                    {{ $this->attendanceRequestAction }}
                </div>

            </div>
        </div>
    </x-filament::section>
    <x-filament-actions::modals/>
</x-filament-widgets::widget>
