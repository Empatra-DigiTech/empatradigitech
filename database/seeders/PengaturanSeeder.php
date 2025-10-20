<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengaturan;


class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaturan::firstOrCreate([
            'website_name' => 'Balai Uji KIR Malang Kotag'
            ],[
            'website_name' => 'Balai Uji KIR Malang Kotag',
            'website_email' => 'kir@malangkota.go.id',
            'website_phone' => '(0341)-1234567',
            'website_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.537169818673!2d112.64262989999999!3d-8.0465577!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62774bb79a3ef%3A0xd164aaf1941467f2!2sBalai%20Uji%20KIR%20Malang%20Kota!5e0!3m2!1sid!2sid!4v1742614235163!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'website_motto' => 'Bekerja Bersama, Bersama Bekerja Untuk Mewujudkan Merdeka Belajar Mudah, Efektif, Ramah, Disiplin, Efisien, Kolaboratif, dan Akuntabel Dalam Melayani ',
            'website_logo' => '',
            'website_address' => 'Jl. Mayjen Sungkono No.06, Arjowinangun, Kec. Kedungkandang, Kota Malang, Jawa Timur 65132',
            ]);
    }
}
