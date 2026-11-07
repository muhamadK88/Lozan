<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * The institute only ever has two administrators. Change the credentials
     * in .env before running this seeder on a production server.
     */
    public function run(): void
    {
        $password = (string) config('lozan.seed_admins.password');

        if ($password === '') {
            throw new \RuntimeException(
                'Set ADMIN_PASSWORD in .env before running the admin seeder.'
            );
        }

        foreach ((array) config('lozan.seed_admins.accounts', []) as $admin) {
            Admin::query()->updateOrCreate(
                ['email' => (string) $admin['email']],
                [
                    'name' => (string) $admin['name'],
                    'password' => $password,
                ],
            );
        }
    }
}
