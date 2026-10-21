<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Database\Seeder;

/**
 * Impor 51 transaksi dari pembukuan Excel "KAS CV EMPATRA DIGITECH" (sheet "Kas 2025").
 *
 * Catatan:
 * - Tanggal yang di Excel tersimpan sebagai tanggal (bukan teks) terbaca terbalik
 *   (dd/mm jadi mm/dd). Di sini sudah dikoreksi; urutan kronologis & saldo akhir
 *   (Rp 9.991.046) sudah dicocokkan dengan kolom "Saldo Akhir" di Excel.
 * - Kategori di Excel (teks bebas) dipetakan ke master kategori keuangan.
 *   Deskripsi asli (nama klien/keperluan) tetap dipertahankan di kolom deskripsi.
 * - Aman dijalankan ulang: transaksi dengan No. Transaksi yang sudah ada dilewati.
 *
 * Jalankan: php artisan db:seed --class=FinanceTransactionSeeder
 */
class FinanceTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // [no_transaksi, tanggal, deskripsi, jenis, kategori induk, sub kategori, jumlah, metode bayar]
        $rows = [
            ['ED-0001', '2025-05-02', 'Kas Masuk Joki Tugas - Mas Dwi', 'masuk', 'Pendapatan Jasa / Proyek', 'Joki Tugas', 250000, 'Transfer'],
            ['ED-0002', '2025-05-27', 'Kas Masuk DP LMS - Kaigo.Lab', 'masuk', 'Pendapatan Jasa / Proyek', 'Aplikasi', 5000000, 'Transfer'],
            ['ED-0003', '2025-06-04', 'Kas Masuk Buat Logo - Brand Logo', 'masuk', 'Pendapatan Jasa / Proyek', 'Desain / Flyer', 102500, 'Transfer'],
            ['ED-0004', '2025-06-18', 'Kas Masuk Joki Tugas - Mas Dwi', 'masuk', 'Pendapatan Jasa / Proyek', 'Joki Tugas', 250000, 'Transfer'],
            ['ED-0005', '2025-06-28', 'Kas Keluar Hosting - Hostinger 48 Bulan', 'keluar', 'Hosting & Domain', null, 1700697, 'Transfer'],
            ['ED-0006', '2025-07-11', 'Kas Masuk Pelunasan LMS - Kaigo.Lab', 'masuk', 'Pendapatan Jasa / Proyek', 'Aplikasi', 25000000, 'Transfer'],
            ['ED-0007', '2025-07-11', 'Gaji Dev', 'keluar', 'Gaji & Bonus', 'Gaji Developer', 16000000, 'Transfer'],
            ['ED-0008', '2025-07-11', 'Gaji Broker + Sosmed', 'keluar', 'Gaji & Bonus', 'Gaji Broker / Sosmed', 3400000, 'Transfer'],
            ['ED-0009', '2025-07-11', 'Bonus Dev', 'keluar', 'Gaji & Bonus', 'Bonus', 2000000, 'Transfer'],
            ['ED-0010', '2025-07-13', 'Kas Masuk Joki Tugas - Excel', 'masuk', 'Pendapatan Jasa / Proyek', 'Joki Tugas', 200000, 'Transfer'],
            ['ED-0011', '2025-07-21', 'Kas Masuk DP Aplikasi - SIGMA APP', 'masuk', 'Pendapatan Jasa / Proyek', 'Aplikasi', 600000, 'Transfer'],
            ['ED-0012', '2025-07-27', 'Followers Instagram', 'keluar', 'Marketing & Iklan', null, 26500, 'Transfer'],
            ['ED-0013', '2025-08-07', 'Kas Masuk DP Website - PEDAGOGI', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 1000000, 'Transfer'],
            ['ED-0014', '2025-08-14', 'Kas Masuk Flyer - SAS CENTER', 'masuk', 'Pendapatan Jasa / Proyek', 'Desain / Flyer', 300000, 'Transfer'],
            ['ED-0015', '2025-08-14', 'Banner, dll', 'keluar', 'Marketing & Iklan', null, 476500, 'Transfer'],
            ['ED-0016', '2025-08-14', 'Ganci Shopee', 'keluar', 'Lainnya', null, 106000, 'Transfer'],
            ['ED-0017', '2025-08-17', 'Hostinger 48 Bulan', 'keluar', 'Hosting & Domain', null, 1260338, 'Transfer'],
            ['ED-0018', '2025-08-18', 'Lampu EKRAF', 'keluar', 'Operasional', 'ATK & Perlengkapan', 145000, 'Transfer'],
            ['ED-0019', '2025-08-18', 'Kain EKRAF', 'keluar', 'Operasional', 'ATK & Perlengkapan', 36000, 'Transfer'],
            ['ED-0020', '2025-08-18', 'SASC QR', 'masuk', 'Pendapatan Jasa / Proyek', 'Lainnya', 2000000, 'Transfer'],
            ['ED-0021', '2025-10-16', 'Domain PEDAGOGI', 'keluar', 'Hosting & Domain', null, 279955, 'QRIS'],
            ['ED-0022', '2025-10-18', 'PEDAGOGI', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 4800000, 'Transfer'],
            ['ED-0023', '2025-10-18', 'Gaji Dev', 'keluar', 'Gaji & Bonus', 'Gaji Developer', 5000000, 'Transfer'],
            ['ED-0024', '2025-10-20', 'SAS CENTER', 'masuk', 'Pendapatan Jasa / Proyek', 'Lainnya', 150000, 'Transfer'],
            ['ED-0025', '2025-10-25', 'Daftar Google Developer ', 'keluar', 'Lainnya', null, 450000, 'Transfer'],
            ['ED-0026', '2025-10-26', 'SIGMA APP Pelunasan', 'masuk', 'Pendapatan Jasa / Proyek', 'Aplikasi', 1850000, 'Transfer'],
            ['ED-0027', '2025-11-08', 'SASC Flyer', 'masuk', 'Pendapatan Jasa / Proyek', 'Desain / Flyer', 100000, 'Transfer'],
            ['ED-0028', '2025-11-18', 'Hosting Haryono Motor 48 Bulan', 'keluar', 'Hosting & Domain', null, 687312, 'Transfer'],
            ['ED-0029', '2025-11-19', 'Domain Haryono Motor', 'keluar', 'Hosting & Domain', null, 598997, 'Transfer'],
            ['ED-0030', '2025-11-19', 'DP Website Pak Haryono', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 1000000, 'Tunai'],
            ['ED-0031', '2025-11-20', 'Website Pak Haryono', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 5000000, 'Transfer'],
            ['ED-0032', '2025-12-02', 'Gaji Dev', 'keluar', 'Gaji & Bonus', 'Gaji Developer', 6000000, 'Transfer'],
            ['ED-0033', '2025-12-02', 'Biaya Admin per bulan (8 bulan)', 'keluar', 'Operasional', 'Biaya Admin Bank', 52000, 'Transfer'],
            ['ED-0034', '2025-12-09', 'SASC Flyer', 'masuk', 'Pendapatan Jasa / Proyek', 'Desain / Flyer', 150000, 'Transfer'],
            ['ED-0035', '2026-05-06', 'Biaya Admin per bulan (6 bulan)', 'keluar', 'Operasional', 'Biaya Admin Bank', 39000, 'Transfer'],
            ['ED-0036', '2026-06-28', 'Biaya Admin per bulan (1 bulan)', 'keluar', 'Operasional', 'Biaya Admin Bank', 6500, 'Transfer'],
            ['ED-0037', '2026-06-28', 'Perpanjangan Kaigo Lab', 'masuk', 'Pendapatan Jasa / Proyek', 'Lainnya', 1050000, 'Transfer'],
            ['ED-0038', '2026-06-28', 'Biaya Perpanjangan Kaigo Lab', 'keluar', 'Hosting & Domain', null, 237000, 'Transfer'],
            ['ED-0039', '2026-08-12', 'Biaya Admin per bulan (2 bulan)', 'keluar', 'Operasional', 'Biaya Admin Bank', 13000, 'Transfer'],
            ['ED-0040', '2026-09-18', 'Biaya Admin per bulan (1 bulan)', 'keluar', 'Operasional', 'Biaya Admin Bank', 6500, 'Transfer'],
            ['ED-0041', '2026-09-18', 'Hadiah Anak Reja', 'keluar', 'Lainnya', null, 140160, 'Transfer'],
            ['ED-0042', '2026-09-24', 'Project K3', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 400000, 'Transfer'],
            ['ED-0043', '2026-09-24', 'Domain K3', 'keluar', 'Hosting & Domain', null, 181165, 'Transfer'],
            ['ED-0044', '2026-09-25', 'Project K3 (Lunas)', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 400000, 'Transfer'],
            ['ED-0045', '2026-09-25', 'Gaji Dev', 'keluar', 'Gaji & Bonus', 'Gaji Developer', 600000, 'Transfer'],
            ['ED-0046', '2026-09-29', 'Project K3 V2 (dp)', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 400000, 'Transfer'],
            ['ED-0047', '2026-10-01', 'Biaya Admin per bulan (1 bulan)', 'keluar', 'Operasional', 'Biaya Admin Bank', 6500, 'Transfer'],
            ['ED-0048', '2026-10-02', 'Domain K3 v2', 'keluar', 'Hosting & Domain', null, 181165, 'Transfer'],
            ['ED-0049', '2026-10-02', 'Project K3 V2 Lunas', 'masuk', 'Pendapatan Jasa / Proyek', 'Website', 400000, 'Transfer'],
            ['ED-0050', '2026-10-03', 'Domain limunjaya', 'keluar', 'Hosting & Domain', null, 181165, 'Transfer'],
            ['ED-0051', '2026-10-03', 'Gaji Dev', 'keluar', 'Gaji & Bonus', 'Gaji Developer', 600000, 'Transfer'],
        ];

        foreach ($rows as [$number, $date, $description, $type, $parentName, $childName, $amount, $method]) {
            if (FinanceTransaction::where('transaction_number', $number)->exists()) {
                continue;
            }

            $parent = FinanceCategory::firstOrCreate(
                ['name' => $parentName, 'type' => $type, 'parent_id' => null],
                ['urutan' => 99, 'is_active' => true]
            );

            $category = $parent;
            if ($childName) {
                $category = FinanceCategory::firstOrCreate(
                    ['name' => $childName, 'type' => $type, 'parent_id' => $parent->id],
                    ['urutan' => 99, 'is_active' => true]
                );
            }

            FinanceTransaction::create([
                'transaction_number' => $number,
                'date' => $date,
                'description' => $description,
                'finance_category_id' => $category->id,
                'type' => $type,
                'amount' => $amount,
                'payment_method' => $method,
                'notes' => null,
                'created_by' => null,
            ]);
        }
    }
}
