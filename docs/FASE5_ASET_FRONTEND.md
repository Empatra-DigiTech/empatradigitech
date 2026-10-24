# Fase 5 — Konsistensi CSS, Font, dan Aset Front-end

## Bukti tidak ada regresi visual
Screenshot 10 halaman × (desktop 1366px, mobile 390px), sebelum vs sesudah, dengan data contoh yang sama:
- Gaya terhitung (warna navbar, gradient hero, font body/judul, overflow horizontal): **identik semua**.
- Piksel: halaman Login identik 100%. Di halaman lain baris pertama yang berbeda selalu tepat **459 px (desktop) / 592 px (mobile) dari bawah** = blok peta di footer, akibat perbaikan peta di bawah. Seluruh area di atasnya identik.

## 5.1 Token warna
- **Satu sumber:** `public/assets/css/tokens.css` (warna merek, abu-abu, status, font family). Dimuat sebelum `main.css`. Definisi kembar di `main.css` dan `resources/css/home.css` dihapus.
- **Jebakan yang ditemukan:** `inovasi.css` mendefinisikan ulang `--primary`/`--secondary` dengan arti **terbalik** (primary=navy) beserta `--gray-*`, `--white`. Selama ini tidak berefek hanya karena CSS Vite dimuat belakangan. Definisi bentrok itu dibuang; tampilan tetap sama persis.
- `login.css` (halaman mandiri) diganti namespace `--login-*`. Catatan: `var(--gray-400)` di `login.css` baris ±192 tidak pernah terdefinisi (diabaikan browser sejak dulu).
- `--blog-*` dan `--gl-*` kini alias ke token global (`--blog-primary: var(--secondary)` = navy, `--blog-secondary: var(--primary)` = merah). Gradient Blog/Galeri merah→navy dijaga test.
- **Navbar:** aturan `:has()` (2 salinan) diganti satu aturan `.page-dark-hero .site-navbar:not(.is-scrolled)` di `resources/css/home.css`. Halaman hero gelap menandai `<body>` lewat `@section('body_class', 'page-dark-hero')` (Blog, Galeri). Halaman baru dengan hero gelap cukup menambahkan baris itu.

## 5.2 Font
| Halaman | Sebelum | Sesudah |
|---|---|---|
| Publik | Roboto, Inter 100–900, Nunito, Lato, Mulish (5 keluarga, ±60 varian) | **Inter 300–800** (variabel, 1 berkas) |
| Login | Roboto, Inter, Nunito | Inter 300–700 |
| Lupa/Reset password | 5 keluarga + Roboto lagi | Roboto 300/400/500/700 (satu-satunya yang dipakai `login/style.css`) |
| Pesan auth, halaman error | Mulish / Poppins | Inter 300–700 |
Dasar: komputasi gaya menunjukkan seluruh halaman publik memakai Inter (`--font-body/--font-heading`); keluarga lain tidak dipakai di CSS/Blade publik. `home_temp` (layout lama, tidak dirujuk route) tidak diubah selain memuat `tokens.css`.
Keterbatasan verifikasi: sandbox tidak bisa mengunduh Google Fonts, jadi perbandingan visual font tidak mungkin; yang dibuktikan adalah font-family terhitung dan layout. Cek mata di browser Anda disarankan. Langkah lanjutan opsional: host Inter sendiri (`@fontsource/inter`) agar tanpa dependensi Google.

## 5.3 CSS/JS inline (batch 1)
- Dipindah ke Vite: CSS Invoice create/edit/show (±700 baris) → `resources/css/patra/invoice-*.css`. Dibuktikan di browser: jumlah aturan sama dan nilai terhitung tiap aturan identik dengan CSS inline asli.
- `pdf.blade.php` sengaja tetap inline (DomPDF tidak memuat CSS eksternal Vite).
- Sisa inventaris (belum dikerjakan): 31 view ber-`<style>`, 71 view ber-`<script>` inline. Terbesar: `home/layouts/footer` (1256 baris), `partials/about`, `carousel`, `price`, `portfolio`, `hero`, `pages/kontak`. Kerjakan satu modul per batch.
- Test tidak lagi butuh `npm run build` (`withoutVite()`), jadi bisa jalan di CI.

## 5.4 Audit framework & aset (hanya laporan — tidak ada yang dihapus)
- Publik: Bootstrap 5 (vendor) saja. ✔
- Admin: AdminLTE (Bootstrap 4). `head.blade.php` merujuk `assets/css/patra/css/bootstrap.min.css` dan script `dist/js/pages/patra.js` yang **tidak ada** di repo (404 tiap halaman). Favicon admin menunjuk file yang tidak ada → sudah diperbaiki ke `assets/img/favicon.png`. Dua rujukan lain belum disentuh: pastikan dulu di server produksi apakah berkas itu memang ada.
- **Lupa/Reset password memuat dua Bootstrap sekaligus:** Bootstrap 5 vendor + `login/bootstrap.min.css` (4.3.1) + `kaiadmin.min.css`. Kandidat perbaikan berikutnya (perlu uji visual form).
- KaiAdmin hanya dimuat oleh `forgot_pw`, `reset_pw`, dan `home_temp`.
- `public/assets/js/home`: `demo.js`, `kaiadmin(.min).js`, `kalender.js`, `setting-demo*.js`, `plugin/`, `swiper/` tidak dirujuk satu pun view (kandidat sisa template; verifikasi juga rujukan dinamis di JS sebelum menghapus). `navbar.js` (1 rujukan), `core/` (2).
- Route publik `/VM` dan `/SO` mengembalikan 500 karena view `home.pages.vm.index`/`so` tidak ada (route mati) → bahan Fase 7.
- `raw.zip` masih ter-track git.

## Perbaikan sampingan yang ditemukan
Footer: bila admin menempel **seluruh tag `<iframe ...>`** Google Maps di Pengaturan (seperti nilai bawaan seeder), `src` jadi teks tag dan iframe menampilkan halaman 404 situs sendiri. Kini `src` diambil dari tag; hanya `https://` yang dipakai. Dijaga `FooterMapTest`.
