# Paket perbaikan Empatra Digitech

Dasar: branch `main`, commit `a158597`. Semua dites: 370 test lulus (155 lama + 215 baru).
K1 (kredensial di `_env_example`) sengaja TIDAK disentuh sesuai permintaan.

## Cara pasang (pilih salah satu)

### A. Lewat patch (urut 01 sampai 12)
```
git pull
git apply --check patch/01-k2-sanitasi-trix.patch
git apply patch/01-k2-sanitasi-trix.patch
```
Ulangi untuk 02 sampai 12. Jika `--check` gagal karena CRLF, pakai cara B.

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
| 10 | A6 peringatan bila tafsir tanggal mode auto tidak pasti (file menurun juga dikenali), C3 pesan validasi kalkulator tampil (bukan "kesalahan server") + batas panjang/angka, C5 menu: validasi induk (maks. 2 level, bukan diri sendiri), judul maks. 16 saat edit, hapus menu bersub ditolak, perbaikan redirect `show()` |
| 11 | Ketahanan keuangan: impor (A8 batas panjang no./catatan, A9 tolak angka "(1.000)"/"1e3" dan tahun di luar 2000-2100, A10 duplikat tanpa nomor ikut cek Sampah + peringatan duplikat dalam file, A11 `hash_equals` + semua baris tanpa kategori dilaporkan sekaligus + tolak SESSION_DRIVER=cookie), B3 hapus/pulihkan transaksi dalam transaksi DB, B5 pencarian LIKE aman untuk `%` dan `_` (`App\Support\Like`), B6 batas jumlah/catatan/tanggal dan tutup periode bersamaan |
| 12 | Kerapian: C6 create di luar blok if, C7 hapus data tidak ada memberi pesan jelas (10 controller), C8 fitur Paket jadi list, C9 aturan Galeri Store=Update, C10 string/max, kategori Blog, tanggal Kalender, C11 SO tabel kosong, C12 folder upload seragam, C13 password lama untuk profil, C15 properti controller dideklarasikan, C16 kode mati, B4 ringkasan periode 1 query + whereBetween, A12 kode mati impor |
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

## Catatan untuk patch 10
1. **Hapus menu yang punya sub menu kini ditolak** dengan pesan (sebelumnya sub menu jadi yatim dan hilang dari navbar). Hapus/pindahkan sub menunya dulu.
2. **Judul menu** maksimal 16 karakter juga saat edit. Menu lama yang sudah lebih panjang tetap bisa diedit selama judulnya tidak diubah.
3. **Impor Excel mode Otomatis**: bila tafsir hari/bulan tidak bisa dipastikan, pratinjau menampilkan peringatan dan menyarankan memilih mode manual. Perilaku pemilihan tafsirnya sendiri tidak berubah, kecuali file yang terurut menurun kini dikenali dengan benar.
4. **Kalkulator:** harga maksimal 9.999.999.999.999, nama maksimal 255 karakter, urutan harus bilangan bulat >= 0.

## Catatan untuk patch 11 dan 12
1. **Profil:** ada kolom baru "Password Saat Ini". Wajib diisi hanya bila mengubah email atau password.
2. **Galeri:** edit kini menerima video (sama seperti tambah), maksimal 50 MB. Deskripsi dan tanggal wajib saat tambah.
3. **Blog:** kategori harus salah satu dari 4 pilihan di form. Blog lama dengan kategori lain tetap bisa diedit.
4. **Panjang judul** (title/nama/jabatan) maksimal 255 karakter di semua form admin yang diubah.
5. **Folder upload baru** (data lama tidak dipindah, path di database tetap valid): banner, blog, inovasi, layanan, portofolio, team, so.
6. **Impor Excel:** butuh SESSION_DRIVER selain `cookie`. Baris yang sama persis dalam satu file hanya diberi peringatan (bisa sah), sedangkan baris yang cocok dengan transaksi di Sampah dilewati dengan keterangan.
7. **A7 (awalan `= + - @`)** sengaja tidak diubah: belum ada fitur ekspor, tampilan sudah aman. Netralkan bila nanti ada ekspor CSV/Excel.
8. **Tidak ada migrasi** di patch 11-12.
