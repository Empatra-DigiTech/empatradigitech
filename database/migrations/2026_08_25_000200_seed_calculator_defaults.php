<?php

use App\Models\CalculatorService;
use App\Models\CalculatorFeature;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $services = [
            ['nama_layanan' => 'Website Profil / Company Profile', 'harga_dasar' => 3000000, 'harga_per_halaman' => 300000, 'urutan' => 1],
            ['nama_layanan' => 'Website Toko Online (E-Commerce)', 'harga_dasar' => 6000000, 'harga_per_halaman' => 400000, 'urutan' => 2],
            ['nama_layanan' => 'Aplikasi Mobile', 'harga_dasar' => 12000000, 'harga_per_halaman' => 800000, 'urutan' => 3],
            ['nama_layanan' => 'Sistem / ERP Kustom', 'harga_dasar' => 20000000, 'harga_per_halaman' => 1000000, 'urutan' => 4],
        ];

        foreach ($services as $service) {
            CalculatorService::firstOrCreate(
                ['nama_layanan' => $service['nama_layanan']],
                array_merge($service, ['is_active' => true])
            );
        }

        $features = [
            ['nama_fitur' => 'Integrasi Payment Gateway', 'harga_tambahan' => 1500000, 'urutan' => 1],
            ['nama_fitur' => 'Multi Bahasa', 'harga_tambahan' => 1000000, 'urutan' => 2],
            ['nama_fitur' => 'Dashboard Admin Kustom', 'harga_tambahan' => 2000000, 'urutan' => 3],
            ['nama_fitur' => 'SEO Optimization Lanjutan', 'harga_tambahan' => 1000000, 'urutan' => 4],
            ['nama_fitur' => 'Live Chat / Chatbot', 'harga_tambahan' => 1500000, 'urutan' => 5],
            ['nama_fitur' => 'Integrasi API Pihak Ketiga', 'harga_tambahan' => 2000000, 'urutan' => 6],
        ];

        foreach ($features as $feature) {
            CalculatorFeature::firstOrCreate(
                ['nama_fitur' => $feature['nama_fitur']],
                array_merge($feature, ['is_active' => true])
            );
        }
    }

    public function down(): void
    {
        //
    }
};
