<?php

use App\Models\Faq;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $faqs = [
            [
                'question' => 'Berapa lama waktu pengerjaan website atau aplikasi?',
                'answer' => 'Untuk website profil perusahaan biasanya 2-3 minggu, website dengan fitur khusus (e-commerce, booking, dsb) sekitar 4-8 minggu, dan aplikasi mobile atau sistem/ERP kustom bisa 2-4 bulan tergantung kompleksitas. Estimasi pasti akan kami berikan setelah konsultasi kebutuhan Anda.',
                'urutan' => 1,
            ],
            [
                'question' => 'Apakah domain dan hosting sudah termasuk dalam paket?',
                'answer' => 'Tergantung paket yang dipilih. Kami menyediakan opsi paket lengkap dengan domain dan hosting, atau paket khusus pengembangan saja jika Anda sudah memiliki domain/hosting sendiri. Silakan konsultasikan kebutuhan Anda agar kami bisa rekomendasikan paket yang sesuai.',
                'urutan' => 2,
            ],
            [
                'question' => 'Apakah fitur website/aplikasi bisa disesuaikan dengan kebutuhan bisnis saya?',
                'answer' => 'Bisa. Semua proyek yang kami kerjakan disesuaikan dengan kebutuhan spesifik klien, mulai dari desain, alur fitur, hingga integrasi dengan sistem lain (payment gateway, API pihak ketiga, dsb).',
                'urutan' => 3,
            ],
            [
                'question' => 'Apakah ada garansi setelah website/aplikasi selesai dikerjakan?',
                'answer' => 'Ya, setiap proyek mendapat garansi perbaikan bug/error selama periode tertentu setelah serah terima, tanpa biaya tambahan. Detail durasi garansi akan dijelaskan dalam penawaran.',
                'urutan' => 4,
            ],
            [
                'question' => 'Apakah tersedia layanan maintenance setelah website live?',
                'answer' => 'Tersedia. Kami menyediakan paket maintenance bulanan/tahunan yang mencakup update konten, monitoring keamanan, backup rutin, dan dukungan teknis jika sewaktu-waktu dibutuhkan.',
                'urutan' => 5,
            ],
            [
                'question' => 'Apakah bisa pembayaran dengan sistem cicilan?',
                'answer' => 'Bisa. Umumnya kami menerapkan sistem pembayaran bertahap (DP di awal, pelunasan saat proyek selesai), dan untuk proyek dengan skala besar tersedia skema cicilan yang bisa didiskusikan sesuai kesepakatan.',
                'urutan' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_active' => true])
            );
        }
    }

    public function down(): void
    {
        //
    }
};
