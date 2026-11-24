<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', Rule::unique(User::class)],
            
            // Email dibuat nullable (tidak wajib)
            'email' => [
                'nullable', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique(User::class)
            ],
            
            // NIP nullable (tidak wajib)
            'nip' => [
                'nullable', 
                'string', 
                'max:50', 
                Rule::unique(User::class)->ignore($input['nip'] ?? null)
            ],
            
            'jabatan' => ['required', 'string', 'max:100'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Menangani upload foto profil jika ada
        $pathFoto = null;
        if (isset($input['foto_profil'])) {
            $pathFoto = $input['foto_profil']->store('profil', 'public');
        }

        // Menyimpan data ke database
        return User::create([
            'name' => $input['name'],
            'username' => $input['username'],
            // Jika email kosong, masukkan null ke database
            'email' => $input['email'] ?? null, 
            'nip' => $input['nip'] ?? null, 
            'jabatan' => $input['jabatan'],
            'foto_profil' => $pathFoto,
            'password' => Hash::make($input['password']),
        ]);
    }
}