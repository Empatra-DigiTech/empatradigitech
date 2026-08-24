<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;


class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::firstOrCreate([
            'title' => 'Home'
            ],[
            'title' => 'Home',
        ]);

        Menu::firstOrCreate([
            'title' => 'Layanan'
            ],[
            'title' => 'Layanan',
        ]);

        Menu::firstOrCreate([
            'title' => 'Profil'
            ],[
            'title' => 'Profil',
        ]);

        Menu::firstOrCreate([
            'title' => 'Portofolio'
            ],[
            'title' => 'Portofolio',
        ]);

        Menu::firstOrCreate([
            'title' => 'Inovasi'
            ],[
            'title' => 'Inovasi',
        ]);

        Menu::firstOrCreate([
            'title' => 'Blog'
            ],[
            'title' => 'Blog',
        ]);

        Menu::firstOrCreate([
            'title' => 'Galeri'
            ],[
            'title' => 'Galeri',
        ]);

        Menu::firstOrCreate([
            'title' => 'Kontak'
            ],[
            'title' => 'Kontak',
        ]);
    }
}
