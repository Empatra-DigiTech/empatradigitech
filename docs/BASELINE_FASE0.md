# Baseline & Verifikasi — Fase 0

Tanggal: 2026-10-23 · Basis: commit `5e0af60` (main)

## 1. Kondisi awal
| Item | Hasil |
|---|---|
| Laravel / PHP | 10.50.3 / 8.3.6 (composer: `^8.1`) |
| PHPUnit | 10.5 |
| `npm run build` (Vite 6) | Berhasil |
| Test awal | Hanya `ExampleTest`; `GET /` gagal (DB tidak tersedia/tidak di-seed) |
| Migrasi di SQLite | `migrate:fresh --seed` berhasil (aman dipakai untuk test) |

## 2. Temuan yang sudah diverifikasi ulang
| # | Temuan | Status | Fase |
|---|---|---|---|
| 1 | `InvoiceController` store/update memakai `Request` mentah + `json_decode` tanpa validasi | Terbukti | 2.3 |
| 2 | View `show`/`pdf` invoice membaca `$item['amount']` kiriman browser → 500 bila kosong, nilai bisa dipalsukan (total dihitung server dari qty×rate, tampilan dari `amount`) | **Baru** | 2.3 |
| 3 | `UploadHelper`: cek ekstensi dari nama file klien saja; `$valid_ext=[]` menerima semua ekstensi | Terbukti | 2.2 |
| 4 | `InformasiController` memanggil upload dengan `[]` → ekstensi apa saja (termasuk `.php`) | **Baru** | 2.2 |
| 5 | SVG boleh di-upload (ClientLogo, Galeri) → risiko XSS | **Baru** | 2.2 |
| 6 | Pesan "maksimal 5 MB" tetapi batas sebenarnya `50971520` (~48,6 MB) | Terbukti | 2.2 |
| 7 | Captcha login dikomentari (`LoginRequest`), tidak ada throttle login/kontak | Terbukti | 2.1 |
| 8 | Kolom `email` di `kontaks` UNIQUE → pengunjung sama tidak bisa kirim pesan kedua (jadi error) | Terbukti | 2 |
| 9 | `KontakController` memakai `new Error(...)` tanpa `use`/`\` → class tidak ditemukan saat upload ditolak | **Baru** | 2 |
| 10 | Moderator bisa membuka 9 modul tanpa `role:` (profile, portofolio, inovasi, blog, faq, testimoni, client-logo, calculator-service, calculator-feature) | Terbukti | 3 |
| 11 | `Log::emergency($e->getMessage())` 72× di 28 file + `getMessage()` ke pengguna (dokumen menyebut ±29) | Terbukti (lebih banyak) | 2.4 |
| 12 | 35 view memakai `onsubmit="return confirm("` | Terbukti | 5 |
| 13 | Halaman publik & `/patra/pengaturan` → 500 bila tabel `pengaturans` kosong | **Baru** | 7 |
| 14 | 4 file model berhuruf kecil tidak cocok nama kelas (`galeri`, `informasi`, `inovasi`, `layanan`; plus `kontak`, `home`, `pembelajaran`, `profil`) → `Class not found` di Linux; di Windows tidak terlihat | Terbukti | 7.1 |
| 15 | `Tautan`: cache `tautan_all` di-forget, tetapi `HomeController` tidak meng-cache Tautan | Terbukti (tidak berbahaya) | 7 |
| 16 | `raw.zip` ikut ter-track git; `home_temp` tidak direferensikan | Terbukti | 7 |

## 3. Matriks peran (hasil test otomatis)
| Modul | SuperAdmin | Admin | Moderator |
|---|---|---|---|
| menu, banner, galeri, informasi, layanan, paket, tautan, kontak, invoice, vm, team | ✔ | ✔ | ✘ |
| finance, finance-category, users, pengaturan | ✔ | ✘ | ✘ |
| 9 modul pada temuan #10 | ✔ | ✔ | ✔ (belum dibatasi) |

## 4. Cara menjalankan test
```
php artisan test                  # normal; DB SQLite in-memory, DB asli tidak tersentuh
RUN_PENDING=1 php artisan test    # ikut menjalankan test untuk perbaikan yang belum dibuat (akan gagal sampai Fase 2–5 selesai)
```
Test "pending" otomatis di-skip; hapus `pendingFix()` di test terkait saat perbaikannya selesai.

Hasil baseline: **37 lulus, 14 skip (pending)**; dengan `RUN_PENDING=1`: 13 gagal (sesuai temuan di atas).

Catatan Linux/CI: sebelum temuan #14 diperbaiki, test hanya lulus di Windows/macOS (case-insensitive).

## 5. Checklist untuk Anda (di komputer sendiri)
- [ ] Backup DB: `mysqldump -u root -p NAMA_DB > backup_2026-10-23.sql`
- [ ] Backup `storage/app/public` (salin foldernya)
- [ ] Buat branch: `git checkout -b fix/portfolio-hardening`
- [ ] Pastikan `.env` tidak ter-commit dan `APP_DEBUG=false` di produksi
- [ ] `git apply --check fase0-1.patch` lalu `git apply fase0-1.patch`
- [ ] `php artisan test`
