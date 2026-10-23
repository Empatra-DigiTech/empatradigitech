# Fase 3 — Keuangan & Hak Akses

## 3.2 Matriks peran (hasil akhir, dijaga `tests/fixtures/route_roles.json`)
| Modul | SuperAdmin | Admin | Moderator |
|---|---|---|---|
| Dashboard `/patra` | ✔ | ✔ | ✔ |
| Profile, Portofolio, Blog, FAQ, Testimoni | ✔ | ✔ | ✔ |
| Menu, Banner, Galeri, Informasi, Layanan, Paket, Tautan, Kontak, Kalender, SO, VM, Team, **Inovasi, Client/Partner, Kalkulator (layanan & fitur)**, Invoice | ✔ | ✔ | ✘ |
| Pengaturan, User, Log, Buku Kas (transaksi, kategori, periode, sampah) | ✔ | ✘ | ✘ |

Perubahan dari kondisi sebelumnya: Inovasi, Client/Partner, Kalkulator Layanan, Kalkulator Fitur kini khusus Admin ke atas (sebelumnya terbuka untuk Moderator via URL). Sidebar Moderator kini menampilkan Portofolio, Blog, FAQ, Testimoni.

Bukti: snapshot role seluruh route sebelum/sesudah dibandingkan — dari 197 route hanya 9 grup modul di atas yang berubah, yang lain identik. Middleware `role:` yang terduplikasi (101) dipindah ke atribut route group.
Policy/Gate belum dipakai karena belum ada aturan berbasis kepemilikan resource.

## 3.1 Kebijakan Buku Kas
- **Hapus = soft delete.** Transaksi masuk Sampah (siapa & kapan tercatat), tidak dihitung di saldo, bisa dipulihkan SuperAdmin. Nomor `ED-xxxx` yang sudah terpakai tidak digunakan ulang. Tidak ada hapus permanen dari UI.
- **Tutup periode per bulan** (menu Buku Kas → Periode). Periode tertutup: tambah/ubah/hapus/pulihkan transaksi ditolak (termasuk memindahkan tanggal ke/dari bulan tertutup).
- **Buka kembali** hanya SuperAdmin, wajib alasan ≥ 10 karakter; pelaku, waktu, alasan tersimpan dan masuk log aktivitas.
- **Audit:** model memakai `Loggable` (tabel `logs`) + `Log::info/warning` untuk hapus/pulih/tutup/buka.
- **Performa:** Buku Kas tidak lagi memuat semua transaksi; total dan saldo dihitung di database, daftar dipaginasi 20/halaman. Saldo berjalan tiap baris tetap berasal dari seluruh riwayat (sama dengan kolom Saldo Akhir Excel), diuji sama dengan algoritma lama pada 75 data acak.
- Migrasi baru: `2026_10_24_000000_*` (deleted_at, deleted_by), `2026_10_24_000100_*` (finance_periods). Jalankan `php artisan migrate`.
