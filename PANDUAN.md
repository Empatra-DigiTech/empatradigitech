# Paket perbaikan Empatra Digitech

Dasar: branch `main`, commit `a158597`. Semua dites: 217 test lulus (155 lama + 62 baru).
K1 (kredensial di `_env_example`) sengaja TIDAK disentuh sesuai permintaan.

## Cara pasang (pilih salah satu)

### A. Lewat patch (urut 01 sampai 04)
```
git pull
git apply --check patch/01-k2-sanitasi-trix.patch
git apply patch/01-k2-sanitasi-trix.patch
```
Ulangi untuk 02, 03, 04. Jika `--check` gagal karena CRLF, pakai cara B.

### B. Salin file lengkap (cadangan, tanpa patch)
Salin isi folder `files/` ke root proyek, timpa file yang sama (struktur folder sudah sama dengan proyek).
Tidak ada file yang dihapus.

## Setelah dipasang
```
php artisan test
php artisan view:clear
```
Tidak ada paket Composer baru. Tidak ada migrasi baru.

## Yang perlu Anda ketahui sebelum deploy
1. **Cek status user:** `SELECT status, COUNT(*) FROM users GROUP BY status;`
   Mulai sekarang hanya status `1` (Aktif) yang bisa login. Pastikan semua akun yang harus aktif bernilai 1.
2. **Link reset password lama jadi tidak berlaku** (token kini disimpan sebagai hash). Dampaknya hanya untuk link yang dikirim sebelum deploy dan masih berlaku (maks. 60 menit).
3. **Pengirim email lupa password** kini mengikuti `MAIL_FROM_ADDRESS` dan `MAIL_FROM_NAME` di `.env`. Pastikan keduanya terisi.
4. **Logout sekarang POST.** Tombol di topbar sudah diubah. Bila ada link logout lain buatan sendiri, ubah juga.
5. **Captcha form kontak** aktif otomatis bila `NOCAPTCHA_SECRET` dan `NOCAPTCHA_SITEKEY` terisi di `.env`. Catatan: halaman Kontak publik saat ini memakai tombol "Kirim ke WhatsApp" dan tidak memanggil `POST /kontak`. Bila form itu dipakai lagi, tambahkan `@include('home.partials.captcha')` di dalam `<form>`.
6. **Halaman tidak ditemukan** kini membalas status HTTP 404 (sebelumnya 200).
7. **Pengaturan website:** bila tabel `pengaturans` kosong, logo wajib diunggah pada simpan pertama (kolom `website_logo` tidak boleh null).
8. Aset admin yang tidak terpakai dibuang dari layout (jqvmap, knob, summernote, daterangepicker, sparkline, ionicons, boxicons.js, demo.js). Bila ada halaman yang memakainya dan terlewat oleh pencarian saya, kembalikan baris terkait di `patra/layouts/head.blade.php` atau `script.blade.php`.

## Isi tiap patch
| Patch | Isi |
|---|---|
| 01 | K2: sanitasi HTML Trix (`App\Support\HtmlSanitizer`, dipakai `renderTrix`), view ringkasan memakai `{{ }}` |
| 02 | M1 status user (login, sesi berjalan, simpan di UserController, tidak bisa nonaktif/hapus diri sendiri), M2 route `GambarController` dihapus, M3 lupa password (throttle, pesan netral, token hash, pengirim dari .env) |
| 03 | M5 captcha (paket `anhskohbo/no-captcha` yang sudah ada) dan batas panjang form kontak |
| 04 | R1 logout POST, R2 login tanpa min 8, R3 query views beranda dibuang, R4 nomor invoice, R5 UserController, R6 config ganda, R7 layout, R8 aset tak terpakai, R9 avatar tanpa layanan eksternal, R10 pengaturan kosong, R11 hapus kontak |
