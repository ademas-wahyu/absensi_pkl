<?php

namespace Database\Seeders;

use App\Models\Mentor;
use Illuminate\Database\Seeder;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mentorData = [
            [
                'nama_mentor' => 'Budi Santoso',
                'email' => 'budi.santoso@company.com',
                'no_telepon' => '08123456789',
                'divisi_id' => 1, // IT Support
                'keahlian' => 'Network Administration, Server Management, Troubleshooting',
            ],
            [
                'nama_mentor' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@company.com',
                'no_telepon' => '08234567890',
                'divisi_id' => 2, // Frontend Development
                'keahlian' => 'React, Vue.js, Tailwind CSS, JavaScript',
            ],
            [
                'nama_mentor' => 'Ahmad Zaki',
                'email' => 'ahmad.zaki@company.com',
                'no_telepon' => '08345678901',
                'divisi_id' => 3, // Backend Development
                'keahlian' => 'Laravel, Node.js, PostgreSQL, REST API',
            ],
            [
                'nama_mentor' => 'Dewi Lestari',
                'email' => 'dewi.lestari@company.com',
                'no_telepon' => '08456789012',
                'divisi_id' => 4, // UI/UX Design
                'keahlian' => 'Figma, Adobe XD, User Research, Prototyping',
            ],
            [
                'nama_mentor' => 'Rizky Pratama',
                'email' => 'rizky.pratama@company.com',
                'no_telepon' => '08567890123',
                'divisi_id' => 5, // Mobile Development
                'keahlian' => 'Flutter, React Native, Kotlin, Swift',
            ],
            [
                'nama_mentor' => 'Maya Anggraini',
                'email' => 'maya.anggraini@company.com',
                'no_telepon' => '08678901234',
                'divisi_id' => 6, // Quality Assurance
                'keahlian' => 'Manual Testing, Automation Testing, Selenium',
            ],
            [
                'nama_mentor' => 'Doni Hermawan',
                'email' => 'doni.hermawan@company.com',
                'no_telepon' => '08789012345',
                'divisi_id' => 1, // IT Support
                'keahlian' => 'System Administration, Cloud Computing, AWS',
            ],
            [
                'nama_mentor' => 'Rina Kusuma',
                'email' => 'rina.kusuma@company.com',
                'no_telepon' => '08890123456',
                'divisi_id' => 2, // Frontend Development
                'keahlian' => 'Angular, TypeScript, SASS, Responsive Design',
            ],
        ];

        foreach ($mentorData as $mentor) {
            Mentor::query()->create($mentor);
        }
    }
}
