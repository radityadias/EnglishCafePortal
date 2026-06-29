function initiateGeolookup() {
    const btn = document.getElementById('btn-absen');
    const btnText = document.getElementById('btn-text');

    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung deteksi lokasi.');
        return;
    }

    // Transform button layout state to loading
    btn.disabled = true;
    btnText.innerText = 'Mengunci Sinyal Satelit...';

    // Core options forcing clean satellite telemetry hooks
    const geoOptions = {
        enableHighAccuracy: true, // Forces physical phone/laptop GPS hardware chip initialization
        timeout: 10000,           // Drops connection attempt if response takes over 10s
        maximumAge: 0             // Refuses cached browser coordinates to eliminate spoofing hacks
    };

    // Fetching user public IP string via cloudflare helper tool
    fetch('https://www.cloudflare.com/cdn-cgi/trace')
        .then(res => res.text())
        .then(traceData => {
            const ipLines = traceData.split('\n');
            const ipLine = ipLines.find(line => line.startsWith('ip='));
            return ipLine ? ipLine.split('=')[1] : '127.0.0.1';
        })
        .catch(() => '127.0.0.1')
        .then(userIp => {

            // Fire native location listener
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Dispatches parameters asynchronously up into Livewire backend
                @this.call('processAttendance', lat, lng, userIp).then(() => {
                    btn.disabled = false;
                    btnText.innerText = 'Kirim Kehadiran (GPS)';
                });
                },
                (error) => {
                    btn.disabled = false;
                    btnText.innerText = 'Kirim Kehadiran (GPS)';

                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Akses lokasi ditolak! Anda wajib memberikan izin lokasi di setelan browser.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Gagal mengunci posisi. Sinyal GPS lemah atau Anda berada dalam ruangan tertutup.");
                            break;
                        case error.TIMEOUT:
                            alert("Waktu pencarian lokasi habis. Pastikan GPS Anda aktif dan coba lagi.");
                            break;
                    }
                },
                geoOptions
            );
        });
}
