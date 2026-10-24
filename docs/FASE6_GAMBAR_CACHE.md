# Fase 6 — Optimasi Gambar dan Cache Data Publik

## 6.0 Perbaikan kecil (ditunda dari Fase 5)

| Masalah | Perbaikan |
|---|---|
| Halaman lupa/reset password memuat Bootstrap 5 **dan** Bootstrap 4 | Hanya Bootstrap 4 (`login/bootstrap.min.css`) yang dipertahankan; markup halaman memakai kelas BS4 (`ml-auto`, `btn-block`, `form-group`). Tata letak form tidak berubah (dibandingkan screenshot). |
| Background halaman itu menunjuk `login.jpg` (tidak ada) | Menunjuk `login.png` |
| `<script src="assets/js/login/...">` relatif, 404 di `/auth/forgot_pw` dan `/auth/reset_pw/{token}` | Memakai `URL::to('/')` |
| Admin memuat `assets/css/patra/css/bootstrap.min.css` (404 di setiap halaman) | Baris dihapus (AdminLTE sudah memuat Bootstrap). Komentar `patra.js` yang mati ikut dihapus |
| `apple-touch-icon.png` dan `demo.css` (tidak ada) | `apple-touch-icon` → `favicon.png`; `demo.css` dihapus |

Tes: `FrontendAssetsTest` memindai layout/halaman auth dan gagal bila ada aset lokal yang tidak ada.

## 6.1 Gambar

**Inventarisasi** (`public/assets`, > 200 KB): `SO_New.png` 2,5 MB (6912×3456), `VM_New.png` 2,5 MB (tidak dipakai di mana pun, ditangani di Fase 7),
`bg-404.jpeg` 816 KB, `visimisi.png`/`team.png` ± 340 KB, `bg-kalender.png` 252 KB. `public/dist/img/photo*` adalah demo AdminLTE (Fase 7).
`storage/app/public` kosong di repo; ukuran gambar unggahan produksi perlu dicek di server.

**Gambar statis**: `SO_New.png` → 2560×1280 RGB (2,5 MB → 784 KB). Dibandingkan screenshot halaman `/SO`: tata letak identik,
selisih piksel hanya anti-aliasing tepi. Gambar asli 6912 px juga terbukti menunda layout saat decode. Gambar lain selisihnya < 10% sehingga tidak diubah.

**Lazy loading**: `loading="lazy" decoding="async"` pada gambar di bawah fold (kartu daftar blog/inovasi/portofolio/galeri/tim,
galeri & sidebar portofolio, relasi blog/inovasi, logo footer, gambar pembelajaran). Gambar hero, banner halaman, logo navbar dan
gambar utama halaman detail **tidak** di-lazy. Partial beranda sudah lazy sejak awal.

**Kebijakan upload** (`UploadHelper`, satu-satunya jalur upload):
- JPG/JPEG/PNG: sisi terpanjang maks 1920 px; JPEG kualitas 82 progresif; PNG kompresi maksimal dengan transparansi dipertahankan; orientasi EXIF dikoreksi (GD membuang EXIF).
- Gambar kecil (≤ 1920 px dan ≤ 400 KB) disimpan apa adanya; hasil optimasi yang tidak lebih kecil tidak dipakai.
- Format/ekstensi **tidak diubah** (tanpa WebP) agar data lama dan template tetap kompatibel. GIF/WebP tidak disentuh.
- Berkas asli resolusi penuh **tidak disimpan** (hanya versi optimasi). Simpan master di luar aplikasi bila perlu.
- Gambar > 40 megapiksel dilewati (risiko memori GD). Kegagalan optimasi → berkas asli disimpan, tidak ada error ke pengguna.
- Gambar lama di storage tidak diubah.

## 6.2 Cache data publik

`App\Support\PublicCache` (TTL 1 jam): `pengaturan`, `menu`, `tautan`, `layanan` (beranda), `layananFooter`, `banner`, `kalender`.
Pembatalan otomatis lewat event model (`saved`/`deleted`/`restored`) yang dipasang di `AppServiceProvider::boot`, sehingga
tidak bergantung pada tiap controller.

Bug lama yang ikut beres:
- `layanan_all` (footer) tidak pernah dibatalkan → perubahan Layanan baru tampil di footer setelah ±1 jam.
- `menu_all` dipakai dua query berbeda (`Menu::all()` vs `orderBy('created_at')`) → urutan menu bergantung pada siapa yang mengisi cache lebih dulu.

Tidak di-cache (sengaja): penghitung tampilan, portofolio, blog, dan data berfilter `is_active` (paket, FAQ, testimoni, logo klien, kalkulator).

Batasan: perubahan lewat query builder massal/SQL langsung/seeder tidak memicu event. Panggil `PublicCache::flush()` setelahnya
(atau `php artisan cache:clear`).

**Ukuran** (SQLite lokal, request ke `/` saat cache hangat): 15 → 12 query; `/events` 1 → 0 query. Waktu absolut di SQLite
tidak bermakna; manfaat nyata ada di MySQL dengan latensi per-query.

Tes: `ImageOptimizationTest` (6), `PublicCacheTest` (7).
