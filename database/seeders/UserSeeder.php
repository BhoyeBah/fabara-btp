<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('fabara.admin_seed.email');
        $password = config('fabara.admin_seed.password');

        if (! is_string($email) || trim($email) === '' || ! is_string($password) || $password === '') {
            throw new RuntimeException('Configurez FABARA_ADMIN_EMAIL et FABARA_ADMIN_PASSWORD dans .env avant de lancer ce seeder.');
        }

        $email = strtolower(trim($email));

        Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email', 'password' => ['required', 'min:8', 'regex:/[A-Za-z]/', 'regex:/\d/']]
        )->validate();

        $user = User::firstOrNew(['email' => $email]);
        $user->name = 'Administrateur FABARA';
        $user->password = $password;
        $user->role = User::ROLE_ADMIN;
        $user->email_verified_at ??= now();
        $user->terms_accepted_at ??= now();
        $user->save();
    }
}
