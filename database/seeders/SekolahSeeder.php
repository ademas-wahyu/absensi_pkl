<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sekolah;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolahData = [
            [
                "nama_sekolah" => "SMK Negeri 1 Jakarta",
                "alamat" => "Jl. Budi Utomo No. 7, Jakarta Pusat, DKI Jakarta",
                "no_telepon" => "021-3456789",
            ],
            [
                "nama_sekolah" => "SMK Negeri 2 Bandung",
                "alamat" => "Jl. Ciliwung No. 4, Bandung, Jawa Barat",
                "no_telepon" => "022-7654321",
            ],
            [
                "nama_sekolah" => "SMK Muhammadiyah 1 Surabaya",
                "alamat" => "Jl. Raya Darmo No. 12, Surabaya, Jawa Timur",
                "no_telepon" => "031-5678910",
            ],
            [
                "nama_sekolah" => "SMK Telkom Purwokerto",
                "alamat" =>
                    "Jl. D.I. Panjaitan No. 128, Purwokerto, Jawa Tengah",
                "no_telepon" => "0281-6844442",
            ],
            [
                "nama_sekolah" => "SMK Negeri 1 Bekasi",
                "alamat" => "Jl. Bintara Jaya No. 2, Bekasi Barat, Jawa Barat",
                "no_telepon" => "021-88960050",
            ],
            [
                "nama_sekolah" => "SMK Bina Nusantara Semarang",
                "alamat" => "Jl. Pandanaran No. 123, Semarang, Jawa Tengah",
                "no_telepon" => "024-8312345",
            ],
        ];

        foreach ($sekolahData as $sekolah) {
            Sekolah::create($sekolah);
        }
    }
}
