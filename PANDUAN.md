# Paket perbaikan Empatra Digitech

Dasar: branch `main`, commit `a158597`. Semua dites: 299 test lulus (155 lama + 144 baru).
K1 (kredensial di `_env_example`) sengaja TIDAK disentuh sesuai permintaan.

## Cara pasang (pilih salah satu)

### A. Lewat patch (urut 01 sampai 09)
```
git pull
git apply --check patch/01-k2-sanitasi-trix.patch
git apply patch/01-k2-sanitasi-trix.patch
```
Ulangi untuk 02 sampai 09. Jika `--check` gagal karena CRLF, pakai cara B.

### B. Salin file lengkap (cadangan, tanpa patch)
Salin isi folder `files/` ke root proyek, timpa file yang sama (struktur folder sudah sama dengan proyek).
Tidak ada file yang dihapus.

## Setelah dipasang
```
php artisan optimize:clear
php artisan test
```
**Penting:** `optimize:clear` (atau minimal `php artisan route:clear`) wajib dijalankan. Jika sebelumnya pernah `php artisan route:cache`, route lama tetap dipakai dan beberapa test akan gagal (logout, throttle lupa password, halaman 404), bahkan tombol logout bisa error 405.
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
| 05 | Captcha di halaman login admin (aktif bila kunci reCAPTCHA terisi), helper `App\Support\Captcha`, dan perbaikan kotak "Remember me" yang tidak pernah terkirim |
| 06 | C1 Informasi tidak lagi "berhasil" palsu (wajib file atau link, ubah judul saja tersimpan), C2 validasi URL aman (`App\Rules\SafeUrl`) di Tautan, Informasi, Portofolio, ClientLogo, Team, C4 berkas lama otomatis terhapus saat diganti/dihapus (`DeletesStoredFiles` di 15 model) |
| 07 | A1-A3 impor Excel: hanya xlsx/xls/csv, formula tidak dihitung ulang, batas memori (filter baris/kolom, batas isi zip 50 MB, bebaskan memori) |
| 08 | A4-A5 impor besar: periode tertutup, nomor yang ada, dan kunci duplikat diambil sekali (bukan per baris); nomor transaksi otomatis dengan ulang-coba bila bentrok UNIQUE (`FinanceTransaction::createWithAutoNumber`) |
| 09 | B1-B2 validasi kategori keuangan: jenis kategori harus sama dengan transaksi, kategori nonaktif ditolak (kecuali transaksi lama yang sudah memakainya), hierarki maksimal 2 level, jenis induk = anak, jenis tidak bisa diubah bila sudah dipakai |
| 04 | R1 logout POST, R2 login tanpa min 8, R3 query views beranda dibuang, R4 nomor invoice, R5 UserController, R6 config ganda, R7 layout, R8 aset tak terpakai, R9 avatar tanpa layanan eksternal, R10 pengaturan kosong, R11 hapus kontak |

## Catatan untuk patch 06 dan 07
1. **Informasi:** simpan baru kini wajib mengisi file ATAU link. Field link harus diawali `http://`, `https://`, `mailto:`, `tel:` atau `/`. Alamat seperti `www.contoh.com` (tanpa skema) ditolak.
2. **URL lama di database tidak diubah.** Validasi hanya berlaku saat simpan. Cek data lama: `SELECT * FROM tautans WHERE url NOT REGEXP '^(https?://|mailto:|tel:|/)';` (nama tabel menyesuaikan).
3. **Berkas lama:** pembersihan hanya untuk perubahan setelah patch dipasang. Berkas yang sudah yatim di `storage/app/public` sebelumnya tidak dihapus otomatis. Berkas yang masih dipakai baris lain tidak akan dihapus, dan data soft-delete (user) baru menghapus berkas saat `forceDelete`.
4. **Impor Excel:** format .ods dan HTML/XML yang menyamar sebagai .csv/.txt kini ditolak. Formula tidak dihitung ulang, jadi yang dipakai nilai yang tersimpan Excel. Jika file dibuat program (bukan Excel) dan formulanya tidak punya nilai tersimpan, barisnya ditandai error dengan saran membuka lalu menyimpan ulang di Excel.

## Catatan untuk patch 08 dan 09
1. **Impor 1000 baris:** query pratinjau 1004 menjadi 4; simpan 4006 menjadi 2008 (sisanya insert transaksi dan log yang memang diperlukan).
2. **Nomor transaksi** tetap format `ED-0001`. Bila dua pengguna menyimpan bersamaan, yang kalah otomatis mencoba nomor berikutnya (maks. 5 kali), bukan error.
3. **Kategori lama yang sudah tidak konsisten** (mis. anak dari anak, atau jenis beda) tidak diubah otomatis. Aturan baru berlaku saat disimpan. Cek data lama: `SELECT c.id,c.name FROM finance_categories c JOIN finance_categories p ON p.id=c.parent_id WHERE p.parent_id IS NOT NULL OR p.type<>c.type;`
4. Kategori yang menjadi induk dirinya sendiri kini **ditolak dengan pesan**, bukan diam-diam dikosongkan.
