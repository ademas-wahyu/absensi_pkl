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
                'nama_mentor' => 'Mentor User',
                'email' => 'mentor@example.com',
                'no_telepon' => '081234567890',
                'divisi_id' => 1, // Project
                'keahlian' => 'General Mentoring',
                'is_active' => true,
            ],
        ];

        foreach ($mentorData as $mentor) {
            $mentorRecord = Mentor::query()->firstOrCreate(
                ['email' => $mentor['email']],
                $mentor
            );

            // Buat akun User untuk mentor agar bisa login
            if ($mentorRecord->is_active) {
                // Ambil nama divisi untuk diset ke User
                $divisiName = \App\Models\DivisiAdmin::find($mentorRecord->divisi_id)?->nama_divisi;

                $user = \App\Models\User::firstOrCreate(
                    ['email' => $mentor['email']],
                    [
                        'name' => $mentor['nama_mentor'],
                        'password' => 'password', // Password default 'password'
                        'email_verified_at' => now(),
                        'divisi' => $divisiName,
                        'is_active' => true,
                    ]
                );

                // Jika user sudah ada (firstOrCreate tidak membuat baru, tapi update data divisinya supaya sinkron)
                if ($user->divisi !== $divisiName) {
                    $user->update(['divisi' => $divisiName]);
                }

                // Berikan role mentor
                if (!$user->hasRole('mentor')) {
                    $user->assignRole('mentor');
                }
            }
        }
    }
}
