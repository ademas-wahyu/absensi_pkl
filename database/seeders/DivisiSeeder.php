<?php

namespace Database\Seeders;

use App\Models\DivisiAdmin;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis = [
            'Project',
            'Customer Service',
            'Admin',
            'Manajemen',
            'SEO',
            'Sosial Media',
            'Programmer',
            'Support',
            'Ads',
        ];

        foreach ($divisis as $divisi) {
            DivisiAdmin::firstOrCreate([
                'nama_divisi' => $divisi,
            ]);
        }
    }
}
