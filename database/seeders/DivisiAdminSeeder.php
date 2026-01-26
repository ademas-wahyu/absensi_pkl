<?php

namespace Database\Seeders;

use App\Models\DivisiAdmin;
use Illuminate\Database\Seeder;

class DivisiAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisiData = [
            [
                'nama_divisi' => 'IT Support',
                'deskripsi' => 'Divisi yang menangani infrastruktur teknologi informasi dan troubleshooting sistem',
            ],
            [
                'nama_divisi' => 'Frontend Development',
                'deskripsi' => 'Divisi pengembangan antarmuka pengguna menggunakan React, Vue.js, dan teknologi modern',
            ],
            [
                'nama_divisi' => 'Backend Development',
                'deskripsi' => 'Divisi pengembangan sistem server, API, dan database menggunakan Laravel dan Node.js',
            ],
            [
                'nama_divisi' => 'UI/UX Design',
                'deskripsi' => 'Divisi desain pengalaman dan antarmuka pengguna menggunakan Figma dan Adobe XD',
            ],
            [
                'nama_divisi' => 'Mobile Development',
                'deskripsi' => 'Divisi pengembangan aplikasi mobile untuk Android dan iOS',
            ],
            [
                'nama_divisi' => 'Quality Assurance',
                'deskripsi' => 'Divisi pengujian dan quality control untuk memastikan kualitas produk',
            ],
        ];

        foreach ($divisiData as $divisi) {
            DivisiAdmin::query()->create($divisi);
        }
    }
}
