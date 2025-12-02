<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paket;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        $pakets = [
            // Paket Website
            [
                'tipe' => 'website',
                'nama_paket' => 'Basic',
                'tagline' => 'Untuk Pemula',
                'harga' => 5000000,
                'periode' => 'Per Project',
                'fitur' => [
                    '📊 Student & Teacher Dashboard',
                    '📚 Upload Learning Materials',
                    '📝 Attendance Management',
                    '📋 Basic Assignments & Quizzes',
                    '👥 Class & User Management',
                    '☁️ Hosting (First Year Included)',
                    '📖 User Manual Book & Tutorial Videos',
                    '🔧 Maintenance (First 3 Months Warranty)',
                    '⚙️ Feature Revisions (2x Free)'
                ],
                'is_recommended' => false,
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'tipe' => 'website',
                'nama_paket' => 'Pro',
                'tagline' => 'Paling Populer',
                'harga' => 8500000,
                'periode' => 'Per Project',
                'fitur' => [
                    '✅ All Features in Basic Package',
                    '🎛️ Admin Dashboard',
                    '❓ Auto Quizzes & Question Bank',
                    '📈 Automated Grading System',
                    '📊 Student Progress Monitoring',
                    '📜 Auto-Generated Certificates',
                    '✉️ Email Verification',
                    '👨‍💼 Multi-Admin & Role Management',
                    '🌐 Custom Domain',
                    '📖 User Manual Book & Tutorial Videos',
                    '🔧 Maintenance (First 6 Months Warranty)',
                    '⚙️ Feature Revisions (3x Free)'
                ],
                'is_recommended' => true,
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'tipe' => 'website',
                'nama_paket' => 'Premium',
                'tagline' => 'Fitur Terlengkap',
                'harga' => 15000000,
                'periode' => 'Per Project',
                'fitur' => [
                    '💎 All Features in Pro Package',
                    '🎨 Custom UI/UX Design',
                    '📱 SCORM/HTML5 Content Support',
                    '🎮 Gamification',
                    '📊 Class/Teacher/Student Reports',
                    '📈 Monthly Progress Reports',
                    '🔌 API Integration',
                    '🔧 Maintenance (First 1 Year Warranty)',
                    '⚙️ Feature Revisions (5x Free)'
                ],
                'is_recommended' => false,
                'is_active' => true,
                'urutan' => 3,
            ],

            // Paket Aplikasi Mobile
            [
                'tipe' => 'app',
                'nama_paket' => 'Basic',
                'tagline' => 'Aplikasi Simple',
                'harga' => 8000000,
                'periode' => 'Per Project',
                'fitur' => [
                    '📱 Android & iOS Compatible',
                    '👤 User Authentication',
                    '📊 Basic Dashboard',
                    '🔔 Push Notification',
                    '📸 Image Upload Feature',
                    '🗺️ Google Maps Integration',
                    '📖 User Manual',
                    '🔧 Maintenance (First 3 Months)',
                    '⚙️ Feature Revisions (2x Free)'
                ],
                'is_recommended' => false,
                'is_active' => true,
                'urutan' => 1,
            ],
            [
                'tipe' => 'app',
                'nama_paket' => 'Pro',
                'tagline' => 'Solusi Bisnis',
                'harga' => 12000000,
                'periode' => 'Per Project',
                'fitur' => [
                    '✅ All Features in Basic Package',
                    '💳 Payment Gateway Integration',
                    '📊 Advanced Analytics',
                    '👥 Multi-User Management',
                    '💬 In-App Chat',
                    '🔐 Enhanced Security',
                    '📱 Admin Panel Mobile',
                    '🔧 Maintenance (First 6 Months)',
                    '⚙️ Feature Revisions (4x Free)'
                ],
                'is_recommended' => true,
                'is_active' => true,
                'urutan' => 2,
            ],
            [
                'tipe' => 'app',
                'nama_paket' => 'Enterprise',
                'tagline' => 'Untuk Perusahaan',
                'harga' => 25000000,
                'periode' => 'Per Project',
                'fitur' => [
                    '💎 All Features in Pro Package',
                    '🎨 Custom Branding & UI/UX',
                    '🔌 REST API Development',
                    '☁️ Cloud Infrastructure Setup',
                    '📊 Real-time Data Sync',
                    '🔒 Advanced Encryption',
                    '👨‍💻 Dedicated Developer Support',
                    '🔧 Maintenance (First 1 Year)',
                    '⚙️ Unlimited Feature Revisions'
                ],
                'is_recommended' => false,
                'is_active' => true,
                'urutan' => 3,
            ],
        ];

        foreach ($pakets as $paket) {
            Paket::create($paket);
        }
    }
}
