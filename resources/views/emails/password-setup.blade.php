<x-mail::message>
    # Halo, {{ $user->name }}!

    Akun Anda telah disiapkan oleh administrator.
    Klik tombol di bawah untuk mengatur password dan mulai menggunakan sistem.

    <x-mail::button :url="$setupUrl" color="primary">
        Atur Password Saya
    </x-mail::button>

    Link ini akan kedaluwarsa dalam **{{ $expiresInHours }} jam**.
    Jika Anda tidak merasa memiliki akun ini, abaikan email ini.

    Salam,
    {{ config('app.name') }}
</x-mail::message>
