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
            'website_name' => 'Empatra Digitech'
            ],[
            'website_name' => 'Empatra Digitech',
            'website_email' => 'empatradigitech@gmail.com',
            'website_phone' => '+62 851-5181-1055',
            'website_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2027948.2015691448!2d112.26383157939878!3d-6.910164851766578!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2da393f79feeb5c5%3A0x1030bfbca7cb850!2sJawa%20Timur!5e0!3m2!1sid!2sid!4v1760948941924!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'website_motto' => 'Menjadi mitra terpercaya dalam transformasi digital yang memberdayakan, inovatif, dan berdampak — dengan semangat kolaborasi dan keberanian berkarya.',
            'website_logo' => '',
            'website_address' => 'Sigit, Kedungsigit, Karangan, Kabupaten Trenggalek, Jawa Timur 66361',
            ]);
    }
}
