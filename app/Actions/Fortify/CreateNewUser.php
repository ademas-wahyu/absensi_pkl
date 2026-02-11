<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'divisi' => ['required', 'string', 'max:255'],
            'sekolah' => ['required', 'string', 'max:255'],
            'mentor_id' => ['nullable', 'exists:mentors,id'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            // Handle Dynamic Sekolah Creation
            $sekolahName = $input['sekolah'];
            $sekolah = \App\Models\Sekolah::firstOrCreate(
                ['nama_sekolah' => $sekolahName],
                ['alamat' => null, 'no_telepon' => null] // Default values for new schools
            );

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'divisi' => $input['divisi'],
                'sekolah' => $sekolahName, // Storing name as per current schema
                'mentor_id' => $input['mentor_id'] ?? null,
            ]);

            $user->assignRole('murid');

            return $user;
        });
    }
}
